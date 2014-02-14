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
 
use Fiji\App\Controller;
use Fiji\Mail\Storage\Imap;
use Fiji\Cache\File as Cache;
use Fiji\Factory;
use app\mail\view\widget\addressList as addressListWidget;
use app\mail\view\widget\folderList as folderListWidget;
use app\mail\view\widget\pagination;
use app\mail\view\widget\emailTools;
use app\mail\view\widget\addLabel;

/**
 * Email Message
 */
class mailbox extends Controller
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
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication();
        $this->Req = Factory::getRequest();
        $this->Doc = Factory::getDocument();
        
        // configs
        $this->Config = Factory::getSingleton('config\Mail');
        
        // requests
        $this->folder = trim($this->Req->getVar('folder'));
        $view = trim($this->Req->getVar('view', 'mailbox'));
        $this->searchQuery = trim($this->Req->getVar('q'));
        
        //makesure user is logged in
        if (!$this->User->isAuthenticated()) {
            $this->App->setReturnUrl($this->Req->getUri());
            $this->App->redirect('?app=auth', 'Please login to access your email.');
        }
        
        // user imap configs
        if (!isset($this->User->imapOptions)) {
            throw new \Exception('Error accessing your email account');
        }
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
		$this->Doc->head->addJavascript('public/js/mail.keyboardshortcuts.js');
        
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
        $perPage = $this->Config->get('messagesPerPage', 10);
        
        // current page
        $page = intval($this->Req->getVar('p', 1));
        
        // separate pagination html
        $paginationWidget = new pagination($page, $perPage, count($ids));
        $start = $paginationWidget->getStart();
        $end = $paginationWidget->getEnd();
        
        // @todo language
        // move to folder tool
        $folderListWidget = new folderListWidget('folder-list-move', '<i class="icon-folder-open"></i>');
        
        // more tool
        $toolsWidget = new emailTools('email-tools', '<i class="icon-flag"></i>');
        $toolsWidget->addLink('read', 'Mark as Read');
        $toolsWidget->addLink('unread', 'Mark as Unread');
        $toolsWidget->addLink('star', 'Star');
        $toolsWidget->addLink('unstar', 'Unstar');
        
        // add labels tool
        $addLabelWidget = new addLabel('addlabel-tool', '<i class="icon-tags"></i>');
        $labels = $this->Imap->getAllLabels();
        foreach($labels as $label) {
            $addLabelWidget->addLink($label, strtok($label, '/'));
        }
        
        // select messages tool
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
        
        $sentFolder = $this->Config->get('folders')->get('sent');
        
        // template
        require( __DIR__ . '/../view/mailbox/mailbox.php');
    }

    public function test()
    {
        
        // all flags for all messages in mailbox
        $flags = $this->Imap->getFlags(1, INF);
        // unique flags
        $flags = array_unique(iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($flags))));
        
        echo '<pre>';
        var_dump($flags);
        
    }
	
	public function testMailIds()
	{
		$ids = $this->Imap->getUniqueId();
		$ids = array_reverse(array_keys($ids));
		
		var_dump($ids);
		
		foreach($ids as $id) {
			$uid = $this->Imap->getUniqueId($id);
			
			var_dump($uid);
			
			$id = $this->Imap->getNumberByUniqueId($uid);
			
			var_dump($id);
		}
	}
	
	public function testModel()
	{
		
		$User = Factory::createModel('User');
		var_dump($User);
		
	}

}
