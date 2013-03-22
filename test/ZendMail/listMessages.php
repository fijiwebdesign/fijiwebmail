<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Zend\Loader\StandardAutoloader;
use Zend\Mail\Storage\Imap;

/**
 * Consume an RSS feed and display all of the titles and
 * associated links within.
 */

// autoloading zf2 classes
require_once '../../../zf2/library/Zend/Loader/StandardAutoloader.php';
$loader = new StandardAutoloader(array('autoregister_zf' => true));
$loader->register();

$options = array(
	'host' 	   => 'imap.gmail.com',
	'port' 	   => 993,
	'user'     => 'gabe@fijiwebdesign.com',
	'password' => '@f4cf538$',
	'ssl'      => 'ssl'
);

/**
 * Find a mime part by content type
 * @var $message 
 * @var $contentType String ("text/html", "text/plain")
 */
function findPartByContentType($message, $contentType) 
{
    $foundPart = null;
    foreach (new RecursiveIteratorIterator($message) as $part) {
        try {
            if (strtok($part->contentType, ';') == $contentType) {
                $foundPart = $part;
                break;
            }
        } catch (Zend_Mail_Exception $e) {
            // ignore
        }
    }
    return $foundPart;
}

function decodeTransferText($text, $transferEncoding)
{
    if (strtolower($transferEncoding) == 'base64') {
        return base64_decode($text);
    } else if (strtolower($transferEncoding) == 'quoted-printable') {
        return quoted_printable_decode($text);
    } else {
         return $text;
    }
}

$Imap = new Imap($options);

$Imap->selectFolder('Inbox');

/*
$folders = $Imap->getFolders();

var_dump($folders);
 */
$sizes = $Imap->getSize();

$ids = array_reverse(array_keys($sizes));

//var_dump($sizes);

$c = 1;

foreach($ids as $i) {
    $message = $Imap->getMessage($i);
    echo '<div class="message">';
    echo '<div class="header">' . $message->from . '</div>';
    echo '<div class="header">' . $message->subject . '</div>';
    $part = $message;
    if ($message->isMultipart()) {
        echo "Multipart Mime Message:";
        if (!$part = findPartByContentType($message, 'text/html')) {
            $part = findPartByContentType($message, 'text/plain');
        }
    }

    $transferEncoding = '';
    if (isset($part->{'Content-Transfer-Encoding'})) {
        $transferEncoding = $part->getHeader('Content-Transfer-Encoding', 'string');
    }
    $content = decodeTransferText($part->getContent(), $transferEncoding);
    
    echo '<div class="content">' . $content . '</div>';
    echo '</div>'; 
    
    if ($c == 1) {
        break;
    }
    $c++;
}

