<?php

namespace app\mail\controller;

/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
use Fiji\Mail\Storage\Imap;
use Fiji\Cache\File as Cache;
use Fiji\Factory;
use app\mail\view\widget\addressList as addressListWidget;
use app\mail\view\widget\folderList as folderListWidget;
use app\mail\view\widget\pagination;
use app\mail\view\widget\emailTools;

/**
 * Email Message
 */
class mailbox extends \Fiji\App\Controller
{
    
    protected $User;
    protected $App;
    protected $Req;
    protected $Doc;
    protected $Config;
    protected $Imap;
    protected $ImapHelper;
    
    public function __construct(\Fiji\App\View $View = null)
    {
        $this->User = Factory::getSingleton('Fiji\App\User');
        $this->App = Factory::getSingleton('Fiji\App\Application');
        $this->Req = Factory::getSingleton('Fiji\App\Request');
        $this->Doc = Factory::getSingleton('Fiji\App\Document');
        
        // configs
        $this->Config = Factory::getSingleton('config\Mail');
        
        // requests
        $this->folder = trim($this->Req->getVar('folder'));
        $view = trim($this->Req->getVar('view', 'mailbox'));
        $this->searchQuery = trim($this->Req->getVar('q'));
        
        //makesure user is logged in
        if (!$this->User->isAuthenticated()) {
            $this->App->setReturnUrl('?app=mail&folder=' . $this->folder . '&q=' . $this->searchQuery);
            $this->App->redirect('?app=auth');
        }
        
        // user imap configs
        $options = $this->User->imapOptions;
        $this->Imap = Factory::getSingleton('Fiji\Mail\Storage\Imap', array($options));
        // select the folder, create it if it doesn't exist
        if ($this->folder) {
            try {
                $this->Imap->selectFolder($this->folder);
            } catch(\Exception $e) {
                $folders = (array) $this->Config->get('folders');
                if (!in_array($this->folder, $folders)) {
                    throw new \Exception('Folder does not exist');
                }
                $this->Imap->createFolder($this->folder);
                $this->Imap->selectFolder($this->folder);
            }
        }
        $this->ImapHelper = Factory::getSingleton('app\mail\helper\Imap', array($this->Imap));
        
        $this->Doc->title = 'Mailbox';
        
        // call the controllers correct method
        parent::__construct($View, false);
        $this->execute($this->Req->get('view', 'mailbox'));
    }

    /**
     * Display the mailbox
     */
    public function mailbox()
    {

        if ($this->searchQuery) {
            $ids = $this->Imap->search(array('TEXT "' . htmlentities($this->searchQuery) . '"'));
            $ids = array_reverse($ids);
        } else {
            $sizes = $this->Imap->getSize(null, $this->folder);
            $ids = array_reverse(array_keys($sizes));
        }
        
        // number of messages to show 
        // @todo configuration
        $perPage = 10;
        
        // current page
        $page = intval($this->Req->getVar('p', 1));
        
        // separate pagination html
        $paginationWidget = new pagination($page, $perPage, count($ids));
        $start = $paginationWidget->getStart();
        $end = $paginationWidget->getEnd();
        
        // @todo language
        $toolsWidget = new emailTools();
        $toolsWidget->addLink('read', 'Mark as Read');
        $toolsWidget->addLink('unread', 'Mark as Unread');
        $toolsWidget->addLink('star', 'Star');
        $toolsWidget->addLink('unstar', 'Unstar');
        
        $select = '<input type="checkbox">';
        $selectWidget = new emailTools('tool-select', $select);
        $selectWidget->addLink('all', 'All');
        $selectWidget->addLink('none', 'None');
        $selectWidget->addLink('read', 'Read');
        $selectWidget->addLink('unread', 'Unread');
        $selectWidget->addLink('flagged', 'Starred');
        $selectWidget->addLink('unflagged', 'Unstarred');
        //$selectWidget->addLink('replied', 'Replied');
        //$selectWidget->addLink('notreplied', 'Not Replied');
        //$selectWidget->addLink('important', 'Important');
        //$selectWidget->addLink('unimportant', 'Unimportant');
        
        $messages = array();
        for($i = $start; $i <= $end; $i++) {
            
            $id = $ids[$i];
            
            // get message headers
            $message = $this->ImapHelper->getMessage($id);
            
            $message->uid = $this->Imap->getUniqueId($id);
            
            $message->fromWidget = new addressListWidget($message->getHeader('from')->getAddressList());
            $message->toWidget = new addressListWidget($message->getHeader('to')->getAddressList());
            
            $messages[] = $message;
            
        }
        
        $folderListWidget = new folderListWidget('folder-list-move', 'Move to');
        
        $sentFolder = $this->Config->get('folders')->get('sent');
        
        // template
        require( __DIR__ . '/../view/mailbox/mailbox.php');
    }

    public function test()
    {
        
        
        $flags = $this->Imap->getFlags(1);
        
        echo 'flags';
        var_dump(array_unique($flags));
        die;
        
    }

}
