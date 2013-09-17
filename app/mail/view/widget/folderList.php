<?php
/**
 * Fiji Mail Server 
 *
 * @author    gabe@fijiwebdesign.com
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

 
namespace app\mail\view\widget;

use Fiji\Factory;
use RecursiveIteratorIterator;
use Fiji\App\Widget;

/**
 * Generate HTML to display folder list (mailboxes)
 */
class folderList extends Widget
{
    public $id; 
    
    public function __construct($id = 'mail-folders', $title = 'Folders')
    {
        $this->id = $id;
        $this->title = $title;
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication();
        $this->Req = Factory::getRequest();
        if (!$this->User->isAuthenticated() || !$this->User->imapOptions) {
            return;
        }
        
        $options = $this->User->imapOptions;
        $this->Imap = Factory::getSingleton('Zend\Mail\Storage\Imap', array($options));
        return $this;
    }
    
    public function render($format = 'html')
    {
    	if (!$this->User->isAuthenticated() || !$this->User->imapOptions) {
            return;
        }
        echo $this->toHtml();
    }
    
    public function toHtml()
    {
    	if (!$this->User->isAuthenticated() || !$this->User->imapOptions) {
            return;
        }
		
        $currFolder = $this->Req->getVar('folder');
        $folders = $this->Imap->getFolders();
        
        $folders = new RecursiveIteratorIterator($folders, RecursiveIteratorIterator::SELF_FIRST);
        $html = '';
        $html .= '<select name="folder" id="' . htmlentities($this->id) . '">';
        $html .= '<option value="">' . $this->title . '</option>';
        foreach ($folders as $localName => $folder) {
            $localName = str_pad('', $folders->getDepth(), '-', STR_PAD_LEFT) .
                         $localName;
            $html .= '<option';
            if (!$folder->isSelectable()) {
                $html .= ' disabled="disabled"';
            }
            if ((string) $folder == $currFolder) {
                $html .= ' selected="selected"';
            }
            $html .= ' value="' . htmlspecialchars($folder) . '">'
                . htmlspecialchars($localName) . '</option>';
        }
        $html .= '</select>';

        return $html;
    }
    
    public function toHtml2()
    {
    	if (!$this->User->isAuthenticated() || !$this->User->imapOptions) {
            return;
        }
		
        $folders = $this->Imap->getFolders();
        $folders = new RecursiveIteratorIterator($folders, RecursiveIteratorIterator::SELF_FIRST);
        
        $html = '';
        foreach ($folders as $localName => $folder) {
            $localName = str_pad('', $folders->getDepth(), '-', STR_PAD_LEFT) . $localName;
            $html .= '<li><a href="#" data-value="' . (!$folder->isSelectable() ? '' : htmlspecialchars($folder))
                . '">' . htmlspecialchars($localName) . '</a></li>';
        }
        
        $html = '<div class="btn-group" id="' . htmlentities($this->id) . '">
            <button class="btn" data-toggle="dropdown">' . $this->title . '</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                ' . $html . '
            </ul>
        </div>';
        
        return $html;
    }
    
}