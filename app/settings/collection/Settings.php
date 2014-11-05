<?php

namespace app\settings\collection;

/**
 * Fiji Framework
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\App\ModelCollection;

/**
 * Settings Collection
 */
class Settings extends ModelCollection
{
    /**
     * Get all settings for a namespace
     */
    public function findByNamespace($namespace)
    {
        $this->find(array('namespace' => $namespace, 'user_id' => 0));
        return $this;
    }
    
    /**
     * Get Settings for a user
     */
    public function findByUser(Fiji\App\Model\User $User, $namespace = false)
    {
        $query = array('user_id' => $this->user_id);
        if ($namespace) {
            $query['namespace'] = $namespace;
        }
        $$this->find($query);
        
        return $this;
    }
}