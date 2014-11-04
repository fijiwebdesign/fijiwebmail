<?php

namespace Fiji\Service;

/**
 * Service interface
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */

use Fiji\Factory;

/**
 * Service handles the retrieve/store of data for domain object models
 * A Service is not tied to a data storage. Each storage has it's own data provider
 * @todo Intercept all data and create an Entity Map. Or should this be implemented in specific DataProvider's UnitOfWork or Service?
 */
class Service
{
    /**
     * Storage specific data provider
     */
    protected $DataProvider;

    /**
     * Set a property on a domain object publically so we trigger it's __set() method
     */
    static function setDomainObjectProperty(DomainObject $DomainObject, $name, $value)
    {
        $DomainObject->$name = $value;
    }

    /**
     * Construct and set the service used to retrieve/store data
     * @param $Service {Fiji\App\Service} Service Instance
     */
    public function __construct(DataProvider $DataProvider = null)
    {
        $this->DataProvider =  isset($DataProvider) ? $DataProvider : Factory::getDataProvider();
    }

    /**
     * Load data to domain object given the ID
     * @
     * @var $id Unique domain object ID
     * @return Array Single associative array of model properties and values
     */
    public function findById(DomainObject $DomainObject, $id)
    {
        $data = $this->DataProvider->findById($DomainObject, $id);
        return $data;
    }

    /**
     * Find first object matching query
     * @return Array Single associative array of model properties and values
     *
     */
    public function findOne(DomainObject $DomainObject, $query = array())
    {
        $data = $this->DataProvider->findOne($DomainObject, $query);
        return $data;
    }

    /**
     * Find  objects matching query
     * @return Array A List of data arrays representing a list of domain objects
     *
     */
    public function find(DomainObject $DomainObject, $query = array(), $start = 0, $limit = 10)
    {
        $data = $this->DataProvider->find($DomainObject, $query, $start, $limit);
        return $data;
    }

    /**
     * Store the object
     * @return Bool success or fail
     *
     */
    public function saveOne(DomainObject $DomainObject)
    {
        return $this->DataProvider->saveOne($DomainObject);
    }

    /**
     * Store the object
     * @return Bool success or fail
     *
     */
    public function save(DomainCollection $DomainCollection)
    {
        return $this->DataProvider->save($DomainCollection);
    }

    /**
     * Delete the object
     * @return Bool success or fail
     * @param Fiji\Service\DomainObject
     */
    public function deleteOne(DomainObject $DomainObject)
    {
        return $this->DataProvider->deleteOne($DomainObject);
    }

    /**
     * Delete the objects in the DomainCollection
     * @return Bool success or fail
     * @param Fiji\Service\DomainCollection
     */
    public function delete(DomainCollection $DomainCollection)
    {
        return $this->DataProvider->delete($DomainCollection);
    }

    /**
     * Retrieves a referenced DomainObject or DomainCollection
     * @param Fiji\Service\DomainObject Parent Domain Object
     * @param Fiji\Service\DomainCollection | Fiji\Service\DomainObject Referenced Object
     * @param String $RefObject property name in $DomainObject
     *
     * @return Fiji\Service\DomainCollection | Fiji\Service\DomainObject
     *
     */
    public function findReference(DomainObject $DomainObject, $RefObject, $name)
    {
        return $this->DataProvider->findReference($DomainObject, $RefObject, $name);
    }

}
