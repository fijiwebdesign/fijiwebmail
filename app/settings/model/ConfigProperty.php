<?php

namespace app\settings\model;

/**
 * ConfigProperty Model
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */

use Fiji\Factory;
use Exception;
use ReflectionProperty;
use Fiji\App\Model;
use Fiji\App\Config;

/**
 * ConfigProperty Model
 * Properties are read from doc comments using reflection
 */
class ConfigProperty extends \Fiji\App\Model
{
    public $type = 'text';

    public $title;

    public $name;

    public $value;

    public $description;

    /**
     * Attributes
     */
    public $attributes = array();

    /**
     * Create model data from Reflection Property
     */
    public function setDataFromReflectionProperty(ReflectionProperty $ReflectionProperty)
    {
        $docblock = $ReflectionProperty->getDocComment();
        $docblock = trim($docblock, "*\/ \n\r\t"); // remove leading /** and trailing */

        $title = '';
        $this->description = '';
        $this->name = $ReflectionProperty->name;
        $this->value = $ReflectionProperty->value;

        $lines = explode("\n", $docblock);
        foreach($lines as $line) {
            $line = trim($line, "\t *");
            if (strlen($line) && $line[0] == '@') {
                $this->parseAttribute($line);
            } else {
                if ($title) {
                    $this->description .= $line;
                } else {
                    $title .= $line;
                }
            }
        }
        $this->title = $title ? $title : $this->name;
        return $this;
    }

    /**
     * Add data from the Config Model
     */
    public function addDataFromConfigModel(Config $ConfigModel)
    {
        $this->value = isset($ConfigModel->{$this->name}) ? $ConfigModel->{$this->name} : $this->value;
    }

    /**
     * Parse the docblock line in form @{attribute} {data}
     */
    public function parseAttribute($line)
    {
        list($attr, $data) = preg_split("/[\s\t]+/", $line, 2);

        if ($attr == '@type') {
            $this->type = $data;
        } else {
            $this->attributes[] = array(substr($attr, 1), $data);
        }
    }

    /**
     * Retrieve a single attribute
     */
    public function getAttribute($name)
    {
        foreach($this->attributes as $attr) {
            if ($name == $attr[0]) {
                return $attr[1];
            }
        }
    }

    /**
     * Retrieve a list of attributes
     */
    public function getAttributeList($name)
    {
        $list = array();
        foreach($this->attributes as $attr) {
            if ($name == $attr[0]) {
                $list[] = $attr[1];
            }
        }
        return $list;
    }

    public function getValueJson()
    {
        if (is_scalar($this->value)) {
            return $this->value;
        } else {
            return json_encode($this->value);
        }
    }
}
