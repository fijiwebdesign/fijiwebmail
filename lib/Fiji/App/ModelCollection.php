<?php

namespace Fiji\App;

/**
 * Fiji Framework
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;
use Fiji\Service\DomainCollection;

/**
 * ModelCollection
 */
class ModelCollection extends DomainCollection
{
    
    /**
     * Custom method calls
     */
    public function __call($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
		
		// loadData...() calls will trigger $this->DomainObject's loadData...() call
		if (stristr($method, 'loadData')) {
            foreach((array) $params[0] as $data) {
            	$model = clone($this->getDomainObject());
				$model->$method($data);
				$this->push($model);
				//var_dump(array($model, $method, $data));
            }
        }
    }
    
    /**
     * Trigger onFind()
     */
    public function find($query = null)
    {
        if ($this->onFind($query) !== false) {
            return parent::find($query);
        }
    }
    
    /**
     * Trigger onSave()
     */
    public function save()
    {
        if ($this->onSave() !== false) {
            return parent::save();
        }
    }
    
    /**
     * Trigger onDelete()
     */
    public function delete()
    {
        if ($this->onDelete() !== false) {
            return parent::delete();
        }
    }

}
