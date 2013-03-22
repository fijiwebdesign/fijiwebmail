<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
use Zend\Mail;
use Fiji\Mail\Storage\Imap;
use Fiji\Cache\File as Cache;
use Fiji\Factory;
use app\mail\view\widget\addressList as addressListWidget;

// @todo handle from router
new ControllerMessage();

/**
 * Email Message
 */
class ControllerMessage
{
    
    protected $User;
    protected $App;
    protected $Req;
    protected $Doc;
    protected $Config;
    protected $Cache;
    protected $Imap;
    protected $ImapHelper;
    
    protected $flags = array(
        'passed'   => Mail\Storage::FLAG_PASSED,
        'answered' => Mail\Storage::FLAG_ANSWERED,
        'seen'     => Mail\Storage::FLAG_SEEN,
        'deleted'  => Mail\Storage::FLAG_DELETED,
        'draft'    => Mail\Storage::FLAG_DRAFT,
        'flagged'  => Mail\Storage::FLAG_FLAGGED
    );
    
    public function __construct()
    {
        $this->User = Factory::getSingleton('Fiji\App\User');
        $this->App = Factory::getSingleton('Fiji\App\Application');
        $this->Req = Factory::getSingleton('Fiji\App\Request');
        $this->Doc = Factory::getSingleton('Fiji\App\Document');
        // configs @todo
        $this->Config = Factory::getSingleton('config\Mail');
        $this->Cache = new Cache();
        // user imap configs
        $options = $this->User->imapOptions;
        if (!$options) {
            $this->App->redirect('?app=auth');
        }
        $this->Imap = Factory::getSingleton('Fiji\Mail\Storage\Imap', array($options));
        $this->folder = $this->Req->getVar('folder');
        if ($this->folder) {
            $this->Imap->selectFolder($this->folder);
        }
        $this->ImapHelper = new app\mail\helper\Imap($this->Imap);
        
        $view = $this->Req->getVar('view', 'message');
        // call the controllers correct method
        if (in_array($view, get_class_methods($this))) {
            try {
                $this->$view();
            } catch(Exception $e) {
                // @todo handle exceptions app level
                die($e->getMessage());
            }
            
        }
    }
     
    /**
     * View a message
     */       
    public function message()
    {
        // we need a session
        if (!$this->User->isAuthenticated()) {
            // set our return path and redirect to login page
            $this->App->setReturnUrl('?app=mail');
            $this->App->redirect('?app=auth');
        }
        
        // email message id (not the uid or Message-ID)
        $uid = $this->Req->getVar('uid', '');
        $id = $this->Imap->getNumberByUniqueId($uid);
        if (!($uid || $id)) {
            die;
        }
        
        
        $folder = $this->Req->getVar('folder', null);
        if ($folder) {
            $this->Imap->selectFolder($folder);
        }
        
        $message = $this->ImapHelper->getMessageHeaders($id);
        $htmlPart = $this->ImapHelper->getMessageHtmlPart($id);
        
        $body = $htmlPart->getContent();
        if ($htmlPart->getContentType() == 'text/plain') {
            $body = nl2br($body);
        }
        
        // html encoded
        $from = trim(htmlentities($message->from, ENT_QUOTES, 'UTF-8'));
        $to = trim(htmlentities($message->to, ENT_QUOTES, 'UTF-8'));
        $subject = trim(htmlentities($message->subject, ENT_QUOTES, 'UTF-8'));
        
        $inReplyTo = null;
        try {
            $inReplyTo = $message->{'Message-Id'};
        } catch (\Exception $e) {}
        
        $fromWidget = new addressListWidget($message->getHeader('from')->getAddressList());
        $toWidget = new addressListWidget($message->getHeader('to')->getAddressList());
        
        // @todo View class
        require __DIR__ . '/view/message/message.php';
        
    }

    /**
     * Compose a message
     */       
    public function compose()
    {
        
        // @todo View class
        require __DIR__ . '/view/message/compose.php';
        
    }

    /**
     * Delete the given list of messages
     */
    public function delete()
    {
        $uids = $this->Req->getVar('uids', array());
        
        // remove messages
        foreach($uids as $uid) {
            
            // delete message
            $this->Imap->removeMessage($this->Imap->getNumberByUniqueId($uid));
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) successfully deleted.'); 
    }
    
    /**
     * Move the messages to another folder
     */
    public function move()
    {
        
        $uids = $this->Req->getVar('uids', array());
        $to = $this->Req->getVar('to', '');
        
        // create sent folder if not exist
        if (!$this->Imap->folderExists($to)) {
            $this->Imap->createFolder($to);
        }
        
        // remote messages
        foreach($uids as $uid) {
            $this->Imap->moveMessage($this->Imap->getNumberByUniqueId($uid), $to);
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) successfully updated.'); 
        
    }
    
    /**
     * Mark the messages as unread/unseen
     */
    public function unread()
    {
        $uids = $this->Req->getVar('uids', array());
        
        // remote messages
        foreach($uids as $uid) {
            $this->Imap->unsetFlags($this->Imap->getNumberByUniqueId($uid), array(Mail\Storage::FLAG_SEEN));
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) marked as unread.'); 
    }
    
    /**
     * Mark the messages as read/seen
     */
    public function read()
    {
        $uids = $this->Req->getVar('uids', array());
        
        // remote messages
        foreach($uids as $uid) {
            $this->Imap->addFlags($this->Imap->getNumberByUniqueId($uid), array(Mail\Storage::FLAG_SEEN));
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) marked as read.'); 
    }
    
    /**
     * Mark the messages as starred/flagged
     */
    public function star()
    {
        $uids = $this->Req->getVar('uids', array());
        
        // remote messages
        foreach($uids as $uid) {
            $this->Imap->addFlags($this->Imap->getNumberByUniqueId($uid), array(Mail\Storage::FLAG_FLAGGED));
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) starred.'); 
    }
    
    /**
     * Mark the messages as unstarred/unflagged
     */
    public function unstar()
    {
        $uids = $this->Req->getVar('uids', array());
        
        // remote messages
        foreach($uids as $uid) {
            $this->Imap->unsetFlags($this->Imap->getNumberByUniqueId($uid), array(Mail\Storage::FLAG_FLAGGED));
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) unstarred.'); 
    }
    
    /**
     * Set the given flag
     */
    public function flag()
    {
        $uids = $this->Req->getVar('uids', array());
        $flag = $this->Req->getVar('flag', '');
        
        // translate flag to known flags
        if (isset($this->flags[$flag])) {
            $flag = $this->flags[$flag];
        }
        
        // remote messages
        foreach($uids as $uid) {
            $id = $this->Imap->getNumberByUniqueId($uid);
            $this->Imap->addFlags($id, array($flag));
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) marked as ' . $flag); 
    }
    
    /**
     * Remove the given flag
     */
    public function unflag()
    {
        $uids = $this->Req->getVar('uids', array());
        $flag = $this->Req->getVar('flag', '');
        
        // translate flag to known flags
        if (isset($this->flags[$flag])) {
            $flag = $this->flags[$flag];
        }
        
        // remote messages
        foreach($uids as $uid) {
            $id = $this->Imap->getNumberByUniqueId($uid);
            $this->Imap->unsetFlags($id, array($flag));
        }
        
        // go to requested
        $this->App->redirect($this->App->getReturnUrl('?app=mail&folder=' . $this->folder), 
            count($uids) . ' email(s) marked as ' . $flag); 
    }
    
    /**
     * Add folders to spam
     */
    public function spam()
    {
        
    }
    
    /**
     * Add messages to archive
     */
    public function arhive()
    {
        
    }
    
}

