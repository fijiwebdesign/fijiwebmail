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
     * The Configuration these settings apply to
     * @param Fiji\App\Config;
     */
    protected $Config;

    public function getConfigModel()
    {
        return Factory::createModel($this->namespace);
    }

    /**
     * @param Array
     */
    public function setData(Array $array = array())
    {
        // set properties other than $this->Properties
        parent::setData($array);

        // our configuration instance
        $Config = $this->getConfigModel();

        // get a Reflection property for each Config property
        $Reflector = new ReflectionClass($Config);
        $this->description = trim($Reflector->getDocComment(), "*\// \r\n");
        $ReflectorProperties = $Reflector->getProperties(ReflectionProperty::IS_PUBLIC);

        // Reflection doesn't have a getDefaultValue() method.
        foreach($ReflectorProperties as $ReflectorProperty) {
            $ReflectorProperty->value = $ReflectorProperty->getValue($Config);
        }

        // create the config properties from properties parsed by Reflection
        $this->Properties = Factory::createModelCollection('app\settings\model\ConfigProperty')
            ->setDataFromReflectionProperty($ReflectorProperties); // set default data from class annotations and values

        // see if we have data from storage
        $Config->sort(array('id' => 'DESC'))->find();

        if (isset($Config->id)) {
            $this->Properties->addDataFromConfigModel($Config);
        }
    }
}
