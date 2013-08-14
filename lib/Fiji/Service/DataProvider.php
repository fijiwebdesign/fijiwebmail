<?php

namespace Fiji\Service;

/**
 * Data Provider interface
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

/**
 * The DataProvider is an interface to different storage services
 * Excample Services: REST, cache, MySQL, memory, memcached etc.)
 * Service handles the retrieve/store of data for domain objects
 * A Service is not tied to a data storage. Each storage has it's own data provider
 * The Application then uses domain objects to populate it's models with data from the service
 */
interface DataProvider
{
    
    /**
     * Find object given an ID
     * @var $id Unique domain object ID
     * @return Array
     */
    public function findById(DomainObject $DomainObject, $id);
    
    /**
     * Find objects matching query
     * @return Array
     */
    public function find(DomainObject $DomainObject, $query = array(), $start = 0, $limit = 10);
    
    /**
     * Find the first object matching query
     * @return Array
     */
    public function findOne(DomainObject $DomainObject, $query = array());
    
    /**
     * Save all Domain Objects in Collection to storage
     * @return Bool
     */
    public function saveOne(DomainObject $DomainObject);
    
    /**
     * Save a Domain Collection to storage
     * @return Bool
     */
    public function save(DomainCollection $DomainCollection);
    
    /**
     * Delete a Domain Object from storage
     * @return Bool
     */
    public function deleteOne(DomainObject $DomainObject);
    
    /**
     * Delete all Domain Objects in Collection from storage
     * @return Bool
     */
    public function delete(DomainCollection $DomainCollection);
    
    

}
