<?php

namespace app\settings\model;

/**
 * Settings Model
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */

use Fiji\Factory;
use ReflectionClass;
use ReflectionProperty;

/**
 * Settings Model
 */
class Settings extends \Fiji\App\Model
{
    public $title;

    public $namespace;

    public $isUser = false;

    public $multi = false;

    public $description;

    /**
     *  @var {app\settings\model\ConfigProperty}
     */
    public $Properties;

    /**
     * @param Array
     */
    public function setData(Array $array = array())
    {
        // set properties other than $this->Properties
        parent::setData($array);

        // $this->Properties is set as ModelCollection with data from ReflectorProperties fo the $namespace config class
        $Config = Factory::getConfig($this->namespace);

        $Reflector = new ReflectionClass($Config);
        $this->description = trim($Reflector->getDocComment(), "*\// \r\n");
        $ReflectorProperties = $Reflector->getProperties(ReflectionProperty::IS_PUBLIC);

        // Reflection doesn't have a getDefaultValue() method.
        foreach($ReflectorProperties as $ReflectorProperty) {
            $ReflectorProperty->value = $ReflectorProperty->getValue($Config);
        }

        // create the config properties from properties parsed by Reflection
        $this->Properties = Factory::createModelCollection('app\settings\model\ConfigProperty');
        $this->Properties->setDataFromReflectionProperty($ReflectorProperties);
    }
}
