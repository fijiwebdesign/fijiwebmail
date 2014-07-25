<?php

namespace app\mail\model;

/**
 * Fiji Cloud Email
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */
 
use Fiji\Factory;

/**
 * Email Attachments
 */
class Attachment extends \Fiji\App\Model
{
    
    public $title;
    
    public $filename;
    
    public $mimetype;
    
    public $content;
    
    public $description;
    
    public $mailbox_id;
    
    public $message_id;
    
    public $user_id;
    
    public function __construct(Array $data = array())
    {
      parent::__construct($data);
    }
    
    /**
     * Parses the Attachment Mime Part for data to populate Model
     * @param \Fiji\Mail\Storage\Message $Attachment
     */
    public function setDataFromMimeAttachment(\Fiji\Mail\Storage\Message $Attachment)
    {
        $ContentDisposition = $Attachment->getHeader('Content-Disposition')->getFieldValue();
        $ContentType = $Attachment->getHeader('ContentType')->getFieldValue();
        
        $mimeType = substr($ContentType, 0, strpos($ContentType, ';'));
        $fileName = '';
        if (preg_match('/name=[\'"]?([^"\']+)["\']?/', $ContentType, $matches)) {
            $fileName = $matches[1];
        } elseif (preg_match('/filename=[\'"]?([^"\']+)["\']?/', $ContentDisposition, $matches)) {
            $fileName = $matches[1];
        } else {
            throw new \Exception('Could not find attachment name.');
        }
        
        $this->filename = $fileName;
        $this->mimetype = strtolower($mimeType);
        $this->title = $fileName;
        $this->content = $Attachment->getContent();
    }
    
    /**
     * @todo use getMimeType() functions
     */
    public function isImage()
    {
        if (strpos($this->mimetype, 'image/') === 0) {
            return true;
        } else {
            $image_exts = array('jpg', 'jpeg', 'gif', 'png');
            foreach($image_exts as $ext) {
                if (strpos($this->filename, $ext) === strlen($this->filename) - strlen($ext)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @todo implement
     */
    function getMimeType()
    {
        $mimetype = false;
        if(function_exists('finfo_fopen')) {
            // open with FileInfo
        } elseif(function_exists('getimagesize')) {
            // open with GD
        } elseif(function_exists('exif_imagetype')) {
           // open with EXIF
        } elseif(function_exists('mime_content_type')) {
           $mimetype = mime_content_type($filename);
        }
        return $mimetype;
    }
    
    /**
     * Handle getting URLs ($this->url);
     */
    public function getUrl($uid)
    {
        $url = '?app=mail&page=message&view=attachment&uid=' . $uid 
            . '&filename=' . htmlspecialchars(urlencode($this->filename));
        return $url;
                            
    }

}
