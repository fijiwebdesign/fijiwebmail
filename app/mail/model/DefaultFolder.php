<?php

namespace app\mail\model;

/**
 * Fiji Cloud Email
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */

use Fiji\Factory;

/**
 * Mail Default Folder Model. Eg: Inbox, Sent, Trash etc. 
 */
class DefaultFolder extends Folder
{
    /**
     * The global identifier for the default folder
     * @var String Eg: inbox, sent, archive, trash, drafts, spam
     */
    public $def_name;

    /**
     * @var String Icon class name
     */
    public $icon;

}
