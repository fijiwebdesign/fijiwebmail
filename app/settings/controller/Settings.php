<?php

namespace app\settings\controller;

/**
 * Settings Application Controller
 *
 * Creates a UI that each app can plug into to display their settings in one place.
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\App\Controller;
use Fiji\Factory;
use Fiji\App\View;

/**
 * Settings Controller
 */
class Settings extends Controller
{

    public function __construct(View $View = null)
    {

        if (!$this->User->isAuthenticated()) {
            $this->App->setReturnUrl('?app=settings');
            $this->App->redirect('?app=auth', 'Login to edit your settings.');
        }

        parent::__construct($View);

        $this->Doc->title = 'Settings';
    }

    /**
     * Check if we are premitted to execute an $action before executing the corresponding method
     */
    protected function onExecute($action)
    {
        if (!$this->AccessControl->isPermitted($action)) {
            throw new Exception('You are not permited to view this page.');
        }

    }

    /**
     * Display the settings index page
     *
     * Settings is the list of models which point to each namespace for the Configuration.
     * We use this so Configurations don't need titles or other model data.
     * Confuration classes extend Fiji\App\Config.
     * Each configuration value has a docblock comment that is read to render the configuration value as form field.
     */
    public function index()
    {
        // @todo Move to Install
        $SettingsCollection = Factory::getSingleton('data\Settings');

        // error in settings
        if (count($SettingsCollection) == 0) {
            throw new Exception('We need the data in data\Settings. '
                . 'Please make sure this file exists and correctly contains the settings meta data.');
        }

        // create the widgets
        $SettingsWidgets = array();
        foreach($SettingsCollection as $Settings) {
            // see if we have data from storage
            $Config = $Settings->getConfigModel();
            $Config->sort(array('id' => 'DESC'))->find();
            if (isset($Config->id)) {
                $Settings->Properties->addDataFromConfigModel($Config);
            }
            $SettingsWidgets[] = Factory::getWidget('app\settings\widget\Settings', array($Settings));
        }

        // get an alternative view
        $View = Factory::getView('app\settings\view\Settings');
        $View->set('header', 'Settings');
        $View->set('SettingsCollection', $SettingsCollection);
        $View->set('SettingsWidgets', $SettingsWidgets);

        $View->display('index');

    }


    /**
     * Display the individual users settings
     */
    public function user()
    {

        $SettingsCollection = Factory::getSingleton('data\SettingsUser');

        if (count($SettingsCollection) == 0) {
            throw new Exception('No settings have been configured. See: data\SettingsUser.');
        }

        // create the widgets
        $SettingsWidgets = array();
        foreach($SettingsCollection as $Settings) {
            if ($Settings->isCollection) {
                $Collection = Factory::createModelCollection($Settings->namespace)->find(array('user_id' => Factory::getUser()->id));
                $SettingsWidgets[] = Factory::getWidget('app\settings\widget\SettingsList', array($Settings, $Collection));
            } else {
                // see if we have data from storage
                $Config = $Settings->getConfigModel();
                $Config->sort(array('id' => 'DESC'))->find();
                if ($Config->id) {
                    $Settings->Properties->addDataFromConfigModel($Config);
                }
                $SettingsWidgets[] = Factory::getWidget('app\settings\widget\Settings', array($Settings));
            }
        }

        // get an alternative view
        $View = Factory::getView('app\settings\view\Settings');
        $View->set('header', 'Your Settings');
        $View->set('SettingsCollection', $SettingsCollection);
        $View->set('SettingsWidgets', $SettingsWidgets);
        $View->set('tab', $this->Req->get('tab'));

        $View->display('user');
    }

    /**
     * Add or edit a mailbox for this user
     */
    public function mailbox()
    {
        // get settings for config\user\Mail
        $SettingsCollection = Factory::getSingleton('data\SettingsUser')
            ->filter(array('namespace' => 'config\\user\\Mail'));

        // make sure we have settings
        if (!$Settings = isset($SettingsCollection[0]) ? $SettingsCollection[0] : null) {
            throw new Exception('Settings for mailbox required to be defined in data\SettingsUser not found.');
        }

        // set data from storage if we have an id
        if ($mailbox_id = $this->Req->get('id')) {
            $Config = $Settings->getConfigModel()->findById($mailbox_id);
            if ($Config->id) {
                if ($Config->user_id != $this->User->id) {
                    throw new Exception('You cannot edit this mailbox since it belongs to a different user.');
                }
                $Settings->Properties->addDataFromConfigModel($Config);
            } else {
                throw new Exception('The mailbox you want to edit was not found!');
            }
        }

        // create our settings wiget from config\user\Mail
        $SettingsWidget = Factory::getWidget('app\settings\widget\Settings', array($Settings));

        // get an alternative view
        $View = Factory::getView('app\settings\view\Settings');
        $View->set('header', 'Mailbox Settings');
        $View->set('Settings', $Settings);
        $View->set('SettingsWidget', $SettingsWidget);

        $View->display('mailbox');
    }

    /**
     * Save the settings
     */
    public function save()
    {
        $namespace = $this->Req->get('namespace');

        // validate we are given a config model
        $namespaces = Factory::getSingleton('data\Settings')->getPropertyList('namespace');
        if (!in_array($namespace, $namespaces)) {
            throw new Exception('Invalid Settings Namespace!');
        }

        $ConfigModel = Factory::createModel($namespace);

        $this->saveConfig($ConfigModel);

        $this->App->redirect($this->App->getReturnUrl('?app=settings'), 'Settings saved!');
    }

    /**
     * Save the settings
     */
    public function userSave()
    {
        $namespace = $this->Req->get('namespace');

        // validate we are given a config model
        $SettingsCollection = Factory::getSingleton('data\SettingsUser')
            ->filter(array('namespace' => $namespace));

        if (count($SettingsCollection) === 0) {
            throw new Exception('Invalid Settings Namespace!');
        }

        $ConfigModel = Factory::createModel($namespace);
        $Settings = $SettingsCollection[0];

        $this->saveConfig($ConfigModel, $this->Req->get('id'));

        $namespace = Factory::getWidget('app\settings\widget\Settings', array($Settings))->getNamespace();

        $this->App->redirect($this->App->getReturnUrl('?app=settings&view=user&tab=' . $namespace), 'Your settings have been saved!');
    }

    /**
     * Save the settings
     */
    protected function saveConfig($ConfigModel, $id = null)
    {
        // set data from storage if we have an id
        if ($id && !$ConfigModel->findById($id)) {
            throw new Exception('The mailbox you want to save was not found!');
        }

        foreach($ConfigModel as $name => $value) {
            if (!is_null($_value = $this->Req->get($name, $value))) {
                // if config model value is non-scalar, assume json_encoded
                if (isset($value) && !is_scalar($value)) {
                    // @todo move to configProperty model. Fix dependency on associative arrays only
                    $_value = json_decode($_value, true);
                }
                $ConfigModel->$name = $_value;
            }
        }

        if ($ConfigModel->save() === false) {
            throw new Exception('Error saving configuration: ' . $namespace);
        }
    }

    /**
     * Delete a mailbox
     */
    public function deleteMailbox()
    {

        $ConfigModel = Factory::createModel('config\user\Mail');

        // set data from storage if we have an id
        if (!$ConfigModel->findById($this->Req->get('id'))) {
            throw new Exception('The mailbox you want to delete was not found!');
        }

        if ($ConfigModel->delete() === false) {
            throw new Exception('Error deleting mailbox ');
        }

        $this->App->redirect($this->App->getReturnUrl('?app=settings&view=user&tab=config_user_Mail'), 'Mailbox deleted!');
    }

}


/**
 * Custom settings exception handling
 */
class Exception extends \Exception {}
