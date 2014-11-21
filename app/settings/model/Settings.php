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
     *  @var Fiji\App\ModelCollection[app\settings\model\ConfigProperty]
     */
    public $Properties;

    /**
     * The Configuration these settings apply to
     * @param Fiji\App\Config;
     */
    protected $ConfigModel;

    /**
     * Is this a collection of settings
     */
    public $isCollection;

    /**
     * The links to actions
     */
    public $links;

    public function getConfigModel()
    {
        if (!$this->ConfigModel) {
            $this->ConfigModel = Factory::createModel($this->namespace);
        }
        return $this->ConfigModel;
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

    }
}
