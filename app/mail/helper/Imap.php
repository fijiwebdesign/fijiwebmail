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

use Zend\Mail\Storage;
use Fiji\Factory;
use Zend\Mail\Storage\Imap as ZendImap;

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
    
    public function __construct(ZendImap $Imap)
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
            } catch (\Exception $e) {
                // ignore
            }
        }
        return $foundPart;
    }
    
    /**
     * Find attachments (inline or attachment)
     * @var $message 
     * @var $contentType String ("attachment", "inline")
     */
    public function getAttachments($message, $disposition = 'attachment') 
    {
        $parts = array();
        foreach (new \RecursiveIteratorIterator($message) as $part) {
            try {
                if (strtok($part->{"Content-Disposition"}, ';') == $disposition) {
                    $parts[] = $part;
                }
            } catch (\Exception $e) {
                // ignore
            }
        }
        return $parts;
    }
    
    /**
     * Returns the email message
     */
    public function getMessage($id)
    {
        $message = $this->Imap->getMessage($id);
        $message->num = $id;
        
        // flags
        $message->seen = $message->hasFlag(Storage::FLAG_SEEN);
        $message->answered = $message->hasFlag(Storage::FLAG_ANSWERED);
        $message->flagged = $message->hasFlag(Storage::FLAG_FLAGGED);
        
        // get labels (cusotm flags)
        $message->labels = $this->getMessageCustomFlags($message);
        
        $message->className = '';
        
        return $message;
    }

    /**
     * Retrieve flags not in standard flag list $this->flags
     * @return Fiji\App\ModelCollection(app\mail\model\label)
     */
    public function getMessageCustomFlags($message)
    {
        $flags = $message->getFlags();
        $flagModelList = Factory::createModelCollection('app\mail\model\Label');
        foreach($flags as $i => $flag) {
            if (!in_array($flag, $this->flags)) {
                $flagModelList[] = Factory::createModel('app\mail\model\Label')->loadDataFromFlag($flag);
            }
        }
        return $flagModelList;
    }

    /**
     * Returns the Message HTML part or default to text part
     */
    public function getMessageHtmlPart($message)
    {
        $part = $message;
        if ($message->isMultipart()) {
            if (!$part = $this->findPartByContentType($message, 'text/html')) {
                $part = $this->findPartByContentType($message, 'text/plain');
            }
        }
        
        return $part;
    }
	
	/**
     * Returns the Message Plain Text part or default to stripping away HTML
     */
    public function getMessagePlainTextPart($message)
    {
        $part = $message;
        if ($message->isMultipart()) {
            if (!$part = $this->findPartByContentType($message, 'text/plain')) {
                $part = $this->findPartByContentType($message, 'text/html');
            }
        }
        
        return strip_tags($part);
    }
    
}
