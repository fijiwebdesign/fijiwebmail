<?php

namespace app\mail\controller;

/**
 * Fiji Cloud Mail
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\App\Controller;
use Fiji\App\View;
use Fiji\Factory;
use Exception;

/**
 * Attachments controller
 */
class attachments extends Controller
{
    
    public $Service;
    
    public function __construct(View $View = null)
    {
        // @todo set from caller
        $View = Factory::getView('app\mail\view\Attachment');
        
        parent::__construct($View);
        
        $this->User = Factory::getUser();
        
        $this->Doc->title = 'Attachments';
    }

    /**
     * Display the attachments owned by this user
     */
    public function index()
    {
        
        $AttachmentList = Factory::createModelCollection('app\mail\model\Attachment');
        $AttachmentList->find(array('user_id' => $this->User->id));
        
        $this->View->set('header', 'Attachments');
        $this->View->set('AttachmentList', $AttachmentList);
        
        $this->View->display('index');
        
    }
    
    /**
     * Display the individual Attachment
     */
    public function attachment()
    {
        $id = Factory::getRequest()->get('id');
        
        $Attachment = Factory::createModel('app\mail\model\Attachment');
        $Attachment->findById($id);
        
        $mail = Factory::createModel('app\mail\model\mail');
        $mail->findById($Attachment->mail_id);
        
        $this->View->set('title', 'Attachment');
        $this->View->set('mail', $mail);
        $this->View->set('Attachment', $Attachment);
        
        $this->View->display('attachment');
    }
    
    /**
     * Display the upload page
     */
    public function upload()
    {
        
        if (!$this->User->isAuthenticated()) {
            $this->App->setReturnUrl('?app=mail&page=attachments&view=upload');
            return $this->App->redirect('?app=auth', 'Please Login!');
        }
        
        // list of all Folders
        $mailList = Factory::createModelCollection('app\mail\model\mail');
        $mailList->find(array('createdBy' => $User->id));
        
        // get an alternative view
        $View = Factory::getView('app\\mail\\view\\mail');
        $View->set('header', 'Upload Images');
        $View->set('mailList', $mailList);
        
        $View->display('upload');
    }

    /**
     * Save the uploads
     */
    public function saveUpload()
    {
        // input
        $mailId = $this->Req->get('mailId');
        $Attachment = $_FILES['Attachment'];
        $title = $this->Req->get('title', $Attachment['name']);
        $description = $this->Req->get('description');
        
        $User = Factory::getUser();
        
        if (!$User->isAuthenticated()) {
            $this->App->setReturnUrl('?app=mail&view=upload');
            return $this->App->redirect('?app=auth', 'Please Login!');
        }
        
        if (!$mailId) {
            throw new Exception('Please select a mail.');
        }
        
        if ($Attachment['error']) {
            throw new Exception('Error uploading file.');
        }
        
        $mail = Factory::createModel('app\mail\model\mail');
        $mail->findById($mailId);
        
        if (!$mail->getId()) {
            throw new Exception('Invalid mail selected.');
        }
        
        $allowedTypes = array('image/jpeg', 'image/jpg', 'image/png');
        if (!in_array($Attachment['type'], $allowedTypes)) {
            throw new mailException('Error file type.');
        }
        
        $secret = sha1(mt_rand() . microtime(true));
        $filename = $this->App->getPathBase() . '/public/Attachment/' . $secret . $Attachment['name'];
        
        if (!move_uploaded_file($Attachment['tmp_name'], $filename )) {
            throw new mailException('Error saving file');
        }

        $Attachment = Factory::createModel('app\mail\model\Attachment');
        $Attachment->title = $title;
        $Attachment->description = $description;
        $Attachment->filename = $filename;
        $Attachment->mail_id = $mail->getId();
        $Attachment->created_by = $User->getId();
        
        if (!$Attachment->save()) {
            throw new mailException('Error saving Attachment to mail');
        }
        
        $this->App->redirect('?app=mail', 'File uploaded successfully!');
        
    }

    public function testsave()
    {
        $Attachment = Factory::createModel('app\mail\model\Attachment');
        $Attachment->id = 8;
        $Attachment->title = 'this is the title 8';
        $Attachment->description = 'nice picture description';
        $Attachment->filename = 'this is the filename';
        
        $Config = new \config\Service();
        $DataProvider = new \service\DataProvider\MySql($Config);
        $Service = new \Fiji\Service\Service($DataProvider);
        $Attachment->setService($Service);
        
        $id = $Attachment->save();
    }
    
    public function testsavemulti()
    {
        $Attachments = Factory::createModelCollection('app\mail\model\Attachment');
        $Attachments->find();
        
        $Attachments[0]->title = 'Changed title!';
        
        $Attachments->save();
    }
    
    public function testfind()
    {
        $models = Factory::createModelCollection('app\mail\model\Attachment');
        $models->find();
        
        var_dump($models);
    }
    
    public function testfindone()
    {
        $model = Factory::createModel('app\mail\model\Attachment');
        $model->findById(18);
        
        var_dump($model);
    }

    public function testdelete()
    {
        $models = Factory::createModelCollection('app\mail\model\Attachment');
        $models->find();
        $models->delete();
        
        var_dump($models);
    }
    
    public function testcreate()
    {
        $model = Factory::createModel('app\mail\model\AttachmentType');
        $model->title = 'Attachment title';
        $model->save(); // should create a table in mysql for this model
    }

}
