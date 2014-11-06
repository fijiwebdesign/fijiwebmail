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
 *
 * @todo Make these onFind() etc. Events dynamic (can be set from outside class inheritence scope)
 */
class ModelCollection extends DomainCollection
{
    
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

    /**
     * Filters Objects in Collection by a field. 
     * Does not retrieve from storage. Only existing objects are filtered.
     * @param $query Array 
     * @return \Fiji\App\ModelCollection
     */
    public function filter($query)
    {
        if ($this->onFilter($query) !== false) {
            return parent::filter($query);
        }
    }
}
