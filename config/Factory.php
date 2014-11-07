<?php
/**
 * Fiji Mail Server
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config;

use Fiji\App\Config;

/**
 * Namespace mappings for loading different classes of the same type
 *
 * The property name maps to the class name.
 * The _ separates namespaces. So Model\User will map to Model_User and lookup that array till it finds a match
 * The dynamic names are translated as such:
 *          {App}           => current App      ie. Factory::getApplication()
 *          {Widget}        => argument widget name
 *          {Model}         => argument model name
 *
 * @example Factory::create('Model\User');
 *              => Factory::resolves( Factory::$Model_User )
 *              => return new app\{App}\model\User' || new Fiji\App\Model\User;     <= Whichever one exists
 *
 * @example Factory::createModel('User');
 *              => Factory::resolves( Factory::$Model_User )
 *              => return new app\{App}\model\User' || new Fiji\App\Model\User;     <= Whichever one exists
 *
 * @example Factory::getWidget('Navigation');
 *              => Factory::resolves( Factory::$Widget )
 *              => return new app\{Factory::getApplication()}\widget\Navigation;    <= assuming it exists. Or tries next in Factory::$Widget array
 *
 */
class Factory extends Config
{
    /**
     * Default model class paths
     */
    public $Model = array(
        'app\\{App}\\model\\{Model}',
        'Fiji\\App\\Model\\{Model}'
    );

    /**
     * Specific class path definition for "User" model
     */
    public $Model_User = array(
        'app\\{App}\\model\\User',
        'Fiji\\App\\Model\\User'
    );

    /**
     *  Paths to look for widgets
     */
    public $Widget = array(
        'app\\{App}\\widget\\{Widget}',
        'app\\{App}\\widget\\{Widget}\\{Widget}',
        'app\\{App}\\view\\widget\\{Widget}',
        'app\\{App}\\view\\widget\\{Widget}\\{Widget}',
        'widget\\{Widget}\\{Widget}',
        'Fiji\\App\\Widget\\{Widget}'
    );


}
