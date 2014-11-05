<?php

namespace app\settings\model;

/**
 * Fiji Cloud Mail
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */

use Fiji\Factory;

/**
 * Application Settings Model
 */
class App extends \config\App
{

    /**
     * Date the App Config is saved
     * @todo fix so we can have protected $date which get's saved to storage
     */
    //protected $date;

    /**
     * User Id that saved
     */
    //protected $user_id;

    /**
     * Map name to storage
     */
    public function getName()
    {
        return 'config_app';
    }

    /**
     * Set the properties of application configuration
     */
    public function onSave()
    {
        // need an interface to push storable keys to our DomainObject. This is too much of a hack.
        //$this->keys = parent::getKeys();
        //$this->keys = array_merge($this->keys, array('date', 'user_id'));

        $User = Factory::getUser();
        $this->date = time();
        $this->user_id = $User->id;
    }

    /**
     * Adds our new date and user_id as storable keys
     */
    public function getKeys()
    {
      $keys = parent::getKeys();
      return array_merge($keys, array('date', 'user_id'));
    }
}
