<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace app\mail\helper;

use \Zend\Mail\Storage;

class Imap {
    
    protected $Imap;
    
    protected $flags = array(
        'passed'   => Storage::FLAG_PASSED,
        'answered' => Storage::FLAG_ANSWERED,
        'seen'     => Storage::FLAG_SEEN,
        'deleted'  => Storage::FLAG_DELETED,
        'draft'    => Storage::FLAG_DRAFT,
        'flagged'  => Storage::FLAG_FLAGGED
    );
    
    public function __construct($Imap)
    {
        $this->Imap = $Imap;
    }
    
    /**
     * Find a mime part by content type
     * @var $message 
     * @var $contentType String ("text/html", "text/plain")
     */
    public function findPartByContentType($message, $contentType) 
    {
        $foundPart = null;
        foreach (new \RecursiveIteratorIterator($message) as $part) {
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
    
    /**
     * Returns the email headers
     */
    public function getMessageHeaders($id)
    {
        $message = $this->Imap->getMessage($id);
        $message->num = $id;
        
        // flags
        $message->seen = $message->hasFlag(Storage::FLAG_SEEN);
        $message->answered = $message->hasFlag(Storage::FLAG_ANSWERED);
        $message->flagged = $message->hasFlag(Storage::FLAG_FLAGGED);
        
        // @todo real implementation. Labels are folders in IMAP or via Gmail IMAP extension
        $labels = array(
            array('warning', 'label-warning'), 
            array('success', 'label-success'), 
            array('important', 'label-important'),
            array('done', '')
            );
        $message->labels = array();
        if (rand(0, 10) > 4) {
            $message->labels[] = $labels[rand(0, 3)];
            if (rand(0, 1)) {
                $message->labels[] = $labels[rand(0, 3)];
            }
            if (!rand(0, 2)) {
                $message->labels[] = $labels[rand(0, 3)];
            }
        }
        
        
        $message->className = '';
        
        return $message;
    }

    /**
     * Returns the Message HTML part or default to text part
     */
    public function getMessageHtmlPart($id)
    {
        $message = $this->Imap->getMessage($id);
        $part = $message;
        if ($message->isMultipart()) {
            if (!$part = $this->findPartByContentType($message, 'text/html')) {
                $part = $this->findPartByContentType($message, 'text/plain');
            }
        }
        
        return $part;
    }
    
}
