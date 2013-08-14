<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\Data;

use Fiji\App\ModelCollection;
use Fiji\Factory;

/**
 * Data to be populated into a ModelCollection/DomainCollection
 */
class Collection extends ModelCollection
{
    
    protected $Model = 'Fiji\App\Model';
    
    /**
     * Data in Collection
     */
    protected $data = array();
    
    /**
     * Instantiate as Widgets ModelCollection
     */
    public function __construct()
    {
        $Model = Factory::createModel($this->Model);
        parent::__construct($Model);
        
        $this->setData($this->data);
    }
    
}



