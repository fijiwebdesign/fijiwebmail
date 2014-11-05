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
use Zend\Mail;
use Fiji\Mail\Storage\Imap;
use Fiji\Cache\File as Cache;
use Fiji\Factory;
use app\mail\view\widget\addressList as addressListWidget;
use Exception;
use app\mail\helper\Imap as ImapHelper;

/**
 * Email Message
 */
class message extends Controller
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
    
    public function __construct(\Fiji\App\View $View = null)
    {
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication();
        $this->Req = Factory::getRequest();
        $this->Doc = Factory::getDocument();
		
		//make sure user is logged in
        if (!$this->User->isAuthenticated()) {
            $this->App->setReturnUrl($this->Req->getUri());
            $this->App->redirect('?app=auth', 'Please login to access your email.');
        }
        // user imap configs
        $options = $this->User->imapOptions;
        if (!$options) {
            throw new \Exception('Error accessing your email account');
        }
        $this->Imap = Factory::getSingleton('Fiji\Mail\Storage\Imap', array($options));
        
        // url params
        $this->folder = $this->Req->getVar('folder');
        $this->page = $this->Req->get('p');
        $this->query = $this->Req->get('q');
        
        // configs
        $this->Config = Factory::getSingleton('config\Mail');
        
        if ($this->folder) {
            $this->Imap->selectFolder($this->folder);
        }
        $this->ImapHelper = new ImapHelper($this->Imap);
        
        parent::__construct($View, false);
        $this->execute($this->Req->getAlphaNum('view', 'message', 'trim'));
    }
     
    /**
     * View a message
     */       
    public function message()
    {
        // email message id (not the uid or Message-ID)
        $uid = $this->Req->getVar('uid', '');
        $id = $this->Imap->getNumberByUniqueId($uid);
        if (!($uid || $id)) {
            throw new Exception('Invalid Message Id');
        }
        
        $message = $this->ImapHelper->getMessage($id);
        $htmlPart = $this->ImapHelper->getMessageHtmlPart($message);
        
        $attachments = $this->ImapHelper->getAttachments($message);
        
        $attachmentModels = array();
        foreach($attachments as $attachment) {
            $attachmentModel = Factory::createModel('app\mail\model\Attachment');
            $attachmentModel->setDataFromMimeAttachment($attachment);
            $attachmentModels[] = $attachmentModel;
        }
        
        $body = $htmlPart->getContent();
        if ($htmlPart->getContentType() == 'text/plain') {
            $body = nl2br($body);
        }
        
        // html encoded
        $from = trim(htmlspecialchars($message->from, ENT_QUOTES, 'UTF-8'));
        $to = trim(htmlspecialchars($message->to, ENT_QUOTES, 'UTF-8'));
        $subject = trim(htmlspecialchars($message->subject, ENT_QUOTES, 'UTF-8'));
        
        $inReplyTo = null;
        try {
            $inReplyTo = $message->{'Message-Id'};
        } catch (\Exception $e) {}
        
        $fromWidget = new addressListWidget($message->getHeader('from')->getAddressList());
        $toWidget = new addressListWidget($message->getHeader('to')->getAddressList());
          
		// load the labels to flags to collection  
		$message->labels = Factory::createModelCollection('app\mail\model\Label');
		$flags = $message->getFlags();
		$message->labels->loadDataFromFlag($flags);
		
		/* test data
		$message->labels->setData(array(
			array('name' => 'test', 'title' => 'test', 'color' => 'cccccc'), 
			array('name' => 'error', 'title' => 'error', 'color' => 'red')
		)); */
		
		// build the pagination
		$sizes = $this->Imap->getSize(null, $this->folder);
		$ids = array_reverse(array_keys($sizes));
		$i = array_search($id, $ids);
		$PaginationWidget = Factory::getWidget('pagination', array($i+1, 1, count($ids)));
		$nextUid = isset($ids[$i+1]) ? $this->Imap->getUniqueId($ids[$i+1]) : 0;
		$prevtUid = isset($ids[$i-1]) ? $this->Imap->getUniqueId($ids[$i-1]) : 0;
		$PaginationWidget->nextUrl = '?app=mail&page=message&uid=' . $nextUid . '&folder=' . $this->folder;
		$PaginationWidget->prevUrl = '?app=mail&page=message&uid=' . $prevtUid . '&folder=' . $this->folder;
		        
        // @todo View class
        require __DIR__ . '/../view/message/message.php';
        
    }

    /**
     * Display the body of the email message
     */
    public function body()
    {
        // page title
        $this->Doc->title = "Email Message";
        
        // email message id (not the uid or Message-ID)
        $uid = $this->Req->getVar('uid', '');
        $id = $this->Imap->getNumberByUniqueId($uid);
		
        if (!($uid || $id)) {
            throw new Exception('Invalid Message Id');
        }
        
        $folder = $this->Req->getVar('folder', null);
        if ($folder) {
            $this->Imap->selectFolder($folder);
        }
        
        $message = $this->ImapHelper->getMessage($id);
        $htmlPart = $this->ImapHelper->getMessageHtmlPart($message);
        
        $body = $htmlPart->getContent();
        if ($htmlPart->getContentType() == 'text/plain') {
            $body = nl2br($body);
        } else {
            // links must open in new window. @todo better implementation
            $body = preg_replace("/<a(.*?)>/i", "<a target=_blank$1>", $body);
        }
                
        // @todo View class
        require __DIR__ . '/../view/message/body.php';
    }

    /**
     * Display an attachment
     * @todo Optimize
     */
    public function attachment()
    {
        // page title
        $this->Doc->title = "Email Message";
        
        // email message id (not the uid or Message-ID)
        $uid = $this->Req->getVar('uid', '');
        $id = $this->Imap->getNumberByUniqueId($uid);
        if (!($uid || $id)) {
            throw new Exception('Invalid Message Id');
        }
        $filename = $this->Req->getVar('filename');
        
        if (!$filename) {
            throw new Exception('No attachment filename');
        }
        
        $folder = $this->Req->getVar('folder', null);
        if ($folder) {
            $this->Imap->selectFolder($folder);
        }
        
        $message = $this->ImapHelper->getMessage($id);
        $attachments = $this->ImapHelper->getAttachments($message);
        
        // find attachment in list
        $attachmentModelMatched = false;
        foreach($attachments as $attachment) {
            $attachmentModel = Factory::createModel('app\mail\model\Attachment');
            $attachmentModel->setDataFromMimeAttachment($attachment);
            
            if ($attachmentModel->filename == $filename) {
                $attachmentModelMatched = true;
                break;
            }
        }
        
        if (!$attachmentModelMatched) {
            throw new Exception('Attachment not found');
        }
        
        if ($this->Req->get('disposition') == 'inline') {
            $this->inlineAttachment($attachmentModel);
        } else {
            $this->downloadAttachment($attachmentModel);
        }
        
        
    }

   /**
     * Display the attachment inline
     */
    private function inlineAttachment(\app\mail\model\Attachment $attachmentModel) {
        
        if (!$attachmentModel->isImage()) {
            throw new Exception('Only Images can be displayed inline.');
        }
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Transfer-Encoding: binary ");

        header("Content-Type: " . $attachmentModel->mimetype);
        header("Content-Disposition: inline; filename=" . $attachmentModel->title);
        header("Content-Length: " . strlen($attachmentModel->content));
        header("Content-Transfer-Encoding: binary");
        echo $attachmentModel->content;
        
        die;
    }

    /**
     * Force download of the attachment
     */
    private function downloadAttachment(\app\mail\model\Attachment $attachmentModel) {
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $attachmentModel->filename);
        header("Content-Transfer-Encoding: binary ");

        header("Content-Type: " . $attachmentModel->mimetype);
        header("Content-Disposition: attachment; filename=" . $attachmentModel->title);
        header("Content-Length: " . strlen($attachmentModel->content));
        header("Content-Transfer-Encoding: binary");
        echo $attachmentModel->content;
        
        die;
    }

    /**
     * Compose a message
     */       
    public function compose()
    {
        // page title
        $this->Doc->title = "Compose Email Message";
           
        // @todo View class
        require __DIR__ . '/../view/message/compose.php';
        
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
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
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
            count($uids) . ' email(s) marked as ' . $flag); 
    }
    
    /**
     * Create a label. Labels are custom flags
     */
    public function addLabel()
    {
        $uids = $this->Req->getVar('uids', array());
        $flag = $this->Req->getVar('flag');
        $color = $this->Req->getVar('color');
        $label = $this->Req->getVar('label');
        
        if (!$label) {
            $label = $flag . '/' . $color;
        }
        
        if (!$label) {
            throw new \Exception('Label not defined');
        }
        
        // remote messages
        foreach($uids as $uid) {
            $id = $this->Imap->getNumberByUniqueId($uid);
            $this->Imap->addFlags($id, array($label));
        }
        
        // go to requested
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
            count($uids) . ' email(s) marked as ' . $label); 
    }

	/**
     * Remove a label. Labels are custom flags
     */
    public function removeLabel()
    {
        $uids = $this->Req->getVar('uids', array());
        $flag = $this->Req->getVar('flag');
        $color = $this->Req->getVar('color');
        $label = $this->Req->getVar('label');
        
        if (!$label) {
            $label = $flag . '/' . $color;
        }
        
        if (!$label) {
            throw new \Exception('Label not defined');
        }
        
        // remote messages
        foreach($uids as $uid) {
            $id = $this->Imap->getNumberByUniqueId($uid);
            $this->Imap->unsetFlags($id, array($label));
        }
        
        // go to requested
        $url = '?app=mail&folder=' . $this->folder . '&p=' . $this->page . '&q=' . $this->query;
        $this->App->redirect($this->App->getReturnUrl($url), 
            'Removed the label ' . $label);
    }
    
}

