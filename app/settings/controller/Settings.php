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
    /**
     * @var {Fiji\App\Model\User}
     */
    protected $User;

    /**
     * @var {Fiji\App\Application}
     */
    protected $App;

    /**
     * @var {Fiji\App\AccessControl}
     */
    protected $AccessControl;

    public function __construct(View $View = null)
    {
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication($this);
        $this->Doc = Factory::getDocument();
        $this->AccessControl = Factory::getAccessControl(get_class($this));

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

        // get an alternative view
        $View = Factory::getView('app\settings\view\Settings');
        $View->set('header', 'Settings');
        $View->set('SettingsCollection', $SettingsCollection);

        $View->display('index');

    }


    /**
     * Display the individual users settings
     */
    public function user()
    {
        $User = Factory::getUser();

        $SettingsCollection = Factory::getSingleton('data\SettingsUser');

        if (count($SettingsCollection) == 0) {
            throw new Exception('No settings have been saved to storage. See: data\Settings for sample.');
        }

        // get an alternative view
        $View = Factory::getView('app\settings\view\Settings');
        $View->set('header', 'Settings');
        $View->set('SettingsCollection', $SettingsCollection);

        $View->display('index');
    }

    /**
     * Save the settings
     */
    public function save()
    {
        $Request = Factory::getRequest();
        $App = Factory::getApplication();
        $User = Factory::getUser();

        $namespace = $Request->get('namespace');

        // validate we are given a config model
        if (!$namespace || !is_subclass_of($namespace, 'Fiji\\App\\Config')) {
            throw new Exception('Invalid Settings Namespace. Must inherit from: Fiji\App\Config.');
        }

        $ConfigModel = Factory::createModel($namespace);

        foreach($ConfigModel->toArray() as $name => $value) {
            $ConfigModel->$name = $Request->get($name, $value);
        }

        if ($ConfigModel->save() === false) {
            throw new Exception('Error saving settings.');
        }

        $App->redirect($App->getReturnUrl('?app=settings'), 'Settings saved!');
    }

}


/**
 * Custom settings exception handling
 */
class Exception extends \Exception {}
