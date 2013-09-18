<?php

namespace app\gallery\controller;

/**
 * Gallery Prototype for Mealku
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

 use Fiji\App\Controller;
use Fiji\Factory;

/**
 * Custom gallery exception handling
 */
class GalleryException extends \Exception {}

/**
 * GalleryController
 */
class Gallery extends Controller
{
    
    public $Service;
    
    public function __construct(\Fiji\App\View $View = null)
    {
        parent::__construct($View);
        
        $this->Doc->title = 'Gallery';
    }

    /**
     * Display the gallery index page
     */
    public function index()
    {
        
        $GalleryList = Factory::createModelCollection('app\gallery\model\Gallery');
        $GalleryList->find();
        
        // get an alternative view
        $View = Factory::getView('app\\gallery\\view\\Gallery');
        $View->set('header', 'Galleries');
        $View->set('GalleryList', $GalleryList);
        
        $View->display('index');
        
    }
    
    /**
     * Display the individual gallery
     */
    public function gallery()
    {
        $galleryId = Factory::getRequest()->get('id');
        
        $Gallery = Factory::createModel('app\gallery\model\Gallery');
        $Gallery->findById($galleryId);
        
        $MediaList = Factory::createModelCollection('app\gallery\model\Media');
        $MediaList->find(array('gallery_id' => $galleryId));
        
        $this->View->set('Gallery', $Gallery);
        $this->View->set('MediaList', $MediaList);
        
        $this->View->display('gallery');
    }
    
    /**
     * Display the individual media
     */
    public function media()
    {
        $id = Factory::getRequest()->get('id');
        
        $Media = Factory::createModel('app\gallery\model\Media');
        $Media->findById($id);
        
        $Gallery = Factory::createModel('app\gallery\model\Gallery');
        $Gallery->findById($Media->gallery_id);
        
        $this->View->set('title', 'Media');
        $this->View->set('Gallery', $Gallery);
        $this->View->set('Media', $Media);
        
        $this->View->display('media');
    }
    
    /**
     * Display the upload page
     */
    public function upload()
    {
        $User = Factory::getUser();
        
        if (!$User->isAuthenticated()) {
            $this->App->setReturnUrl('?app=gallery&view=upload');
            return $this->App->redirect('?app=auth', 'Please Login!');
        }
        
        // list of all galleries
        $GalleryList = Factory::createModelCollection('app\gallery\model\Gallery');
        $GalleryList->find(array('createdBy' => $User->id));
        
        // get an alternative view
        $View = Factory::getView('app\\gallery\\view\\Gallery');
        $View->set('header', 'Upload Images');
        $View->set('GalleryList', $GalleryList);
        
        $View->display('upload');
    }

    /**
     * Save the uploads
     */
    public function saveUpload()
    {
        // input
        $galleryId = $this->Req->get('galleryId');
        $media = $_FILES['media'];
        $title = $this->Req->get('title', $media['name']);
        $description = $this->Req->get('description');
        
        $User = Factory::getUser();
        
        if (!$User->isAuthenticated()) {
            $this->App->setReturnUrl('?app=gallery&view=upload');
            return $this->App->redirect('?app=auth', 'Please Login!');
        }
        
        if (!$galleryId) {
            throw new GalleryException('Please select a gallery.');
        }
        
        if ($media['error']) {
            throw new GalleryException('Error uploading file.');
        }
        
        $Gallery = Factory::createModel('app\gallery\model\Gallery');
        $Gallery->findById($galleryId);
        
        if (!$Gallery->getId()) {
            throw new GalleryException('Invalid Gallery selected.');
        }
        
        $allowedTypes = array('image/jpeg', 'image/jpg', 'image/png');
        if (!in_array($media['type'], $allowedTypes)) {
            throw new GalleryException('Error file type.');
        }
        
        $filename = $this->App->getPathBase() . '/public/media/' . $media['name'];
        
        if (!move_uploaded_file($media['tmp_name'], $filename )) {
            throw new GalleryException('Error saving file');
        }

        $Media = Factory::createModel('app\gallery\model\Media');
        $Media->title = $title;
        $Media->description = $description;
        $Media->filename = $filename;
        $Media->gallery_id = $Gallery->getId();
        $Media->created_by = $User->getId();
        
        if (!$Media->save()) {
            throw new GalleryException('Error saving media to gallery');
        }
        
        $this->App->redirect('?app=gallery', 'File uploaded successfully!');
        
    }

    public function testsave()
    {
        $Media = Factory::createModel('app\gallery\model\Media');
        $Media->title = 'this is the title';
        $Media->description = 'nice picture description';
        $Media->filename = 'this is the filename';
        
        $Media->save();
    }
    
    public function testsavemulti()
    {
        $Medias = Factory::createModelCollection('app\gallery\model\Media');
        $Medias->find();
        
        $Medias[0]->title = 'Changed title!';
        
        $Medias->save();
    }
    
    public function testfind()
    {
        $models = Factory::createModelCollection('app\gallery\model\Media');
        $models->find();
        
        var_dump($models);
    }
    
    public function testfindone()
    {
        $model = Factory::createModel('app\gallery\model\Media');
        $model->findById(18);
        
        var_dump($model);
    }

    public function testdelete()
    {
        $models = Factory::createModelCollection('app\gallery\model\Media');
        $models->find();
        $models->delete();
        
        var_dump($models);
    }
    
    public function testcreate()
    {
        $model = Factory::createModel('app\gallery\model\MediaType');
        $model->title = 'Media title';
        $model->save(); // should create a table in mysql for this model
    }

}
