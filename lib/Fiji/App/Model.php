<?php

namespace Fiji\App;

/**
 * Model
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;
use Fiji\Service\DomainObject;
use Exception;
use Fiji\App\ModelCollection;

/**
 * Base Model
 */
abstract class Model extends DomainObject
{

    /**
     * Construct and set data
     * @param $data {Array} Data Array
     */
    public function __construct(Array $data = null)
    {
        parent::__construct($data);
    }

    /**
     * Get an Array of Domain Object Properties
     * @return Array Associative key/value pairs
     *
     * @todo implement references but make sure DataProvider doesn't save it
     *       May need to create a toData() for this?
     */
    public function toArray()
    {
        // exposed properties
        $array = parent::toArray();
        // convert references to their ids
        foreach($this->References as $name => $class) {
            if (isset($this->$name)) {
                //$array[$name] = $this->$name->toArray();
            }
        }
        // include dynamic properties if Model is Dynamic
        if (!$this->strictOnlyKeys) {
            foreach($this->DynamicProps as $name => $value) {
                if (isset($this->$name)) {
                    $array[$name] = $value;
                }
            }
        }
        
        return $array;
    }

    /**
     * Do not allow modifying the ID once it's set
     */
    public function setId($value)
    {
        if (!isset($this->id)) {
            parent::setId($value);
        }
    }

    /**
     * Trigger onFindById()
     */
    public function findById($id)
    {
        if ($result = $this->onFindById($id) !== false) {
            $model = parent::findById($id);
            $result = $this->afterFindById($model, $id);
        }
        return $result;
    }

    /**
     * Trigger onFind()
     */
    public function find($query = null)
    {
        if ($result = $this->onFind($query) !== false) {
            $model = parent::find($query);
            $result = $this->afterFind($model, $query);
        }
        return $result;
    }

    /**
     * Trigger onSave()
     */
    public function save()
    {
        if ($result = $this->onSave() !== false) {
            $result = parent::save();
            $result = $this->afterSave($result);
        }
        return $result;
    }

    /**
     * Trigger onDelete()
     */
    public function delete()
    {
        if ($result = $this->onDelete() !== false) {
            $result = parent::delete();
            $result = $this->afterDelete($result);
        }
        return $result;
    }

    /**
     * Retrieve a reference to another Model
     */
    public function findReference($name)
    {
        if ($result = $this->onFindReference($name) !== false) {
            $result = parent::findReference($name);
            $result = $this->afterFindReference($result);
        }
        return $result;
    }

    /**
     * Retrieve HTML encoded property
     * @todo decorate from template?
     * @param $name {String} Property name
     * @deprecated violates SRP. Models should not be concerned with view (rendering)
     */
    public function html($name, $quotes = ENT_QUOTES, $encoding = 'utf-8')
    {
        throw new Exception('The Model::html() method is deprecated.');
    }

}
