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
    public $date;

    /**
     * User Id that saved
     */
    public $user_id;

    /**
     * Map name to storage
     */
    public function getObjectName()
    {
        return 'config_app';
    }

    /**
     * Set the properties of application configuration
     */
    public function save()
    {
        unset($this->id);
        $this->date = time();
        $this->user_id = Factory::getUser()->id;

        parent::save();
    }
}
