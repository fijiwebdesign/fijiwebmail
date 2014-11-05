<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\App\Model;

use Fiji\App\Model;

/**
 * User groups
 */
class Group extends Model {
    
    /**
     * @var String Group name
     */
    public $name;
    
    /**
     * @var String Description of group
     */
    public $description;

    /**
     * @var Fiji\App\ModelCollection Users in this group
     */
    protected $UserCollection;

    public function __construct()
    {
        $this->setReference('UserCollection', 'Fiji\App\Model\User');
    }

    
}