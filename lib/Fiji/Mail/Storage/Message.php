<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\Mail\Storage;

use Zend\Stdlib\ErrorHandler;

class Message extends \Zend\Mail\Storage\Message
{
    /**
     * Returns the Content type of message
     */
    public function getContentType()
    {
        $contentType = isset($this->contentType) ? $this->contentType : '';
        return strtok($contentType, ';');
    }
    
    /**
     * Returns the character set encoding of mime part
     */
    public function getCharset()
    {
        $charset = false;
        $contentType = isset($this->contentType) ? $this->contentType : '';
        if (preg_match('/charset="?([^;"]+)/i', $contentType, $matches)) {
            $charset = $matches[1];
        }
        return $charset;
    }
    
    /**
     * Get content decoded
     */
    public function getContent($charset = 'UTF-8')
    {
        $transferEncoding = '';
        if (isset($this->{'Content-Transfer-Encoding'})) {
            $transferEncoding = $this->getHeader('Content-Transfer-Encoding', 'string');
        }
        return $this->decodeTransferText(parent::getContent(), $transferEncoding, $charset);
    }
    
    /**
     * Decodes transfer text and converts charset to desired $outputCharset
     */
    protected function decodeTransferText($text, $transferEncoding, $outputCharset = 'UTF-8')
    {
        if (strtolower($transferEncoding) == 'base64') {
            $text = base64_decode($text);
        } else if (strtolower($transferEncoding) == 'quoted-printable') {
            $text = quoted_printable_decode($text);
        } else {
            $text = $text;
        }
        if ($charset = $this->getCharset()) {
            if ($outputCharset != $charset) {
                if ($utf8Text = iconv($charset, $outputCharset, $text)) {
                    $text = $utf8Text;
                }
            }
        }
        return $text;
    }
    
}
