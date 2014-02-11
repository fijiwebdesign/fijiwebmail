<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace data;

use Fiji\Data\Collection;

/**
 * Default Widgets
 * 
 * name: name of widget (used as className in HTML/JSON/XML rendering)
 * title: Title of widget (displayed on page)
 * position: where widget will be loaded
 * class: class called to render the widget
 * published: published state (1 = published, 0 = unpublished)
 */
class Widgets extends Collection
{
    
    protected $Model = 'Fiji\App\Model\Widget';
    
    /**
     * Widgets in Collection
     */
    protected $data = array(
    	array(
            'name' => 'user-profile',
            'title' => 'User Profile',
            'position' => 'navigation',
            'class' =>'widget\userProfile\userProfile',
            'published' => 1
        ),
        array(
            'name' => 'mail-nav',
            'title' => 'Email Navigation',
            'position' => 'navigation',
            'class' =>'widget\navigation\navigation',
            'published' => 1
        ),
        array(
            'name' => 'header',
            'title' => 'Header',
            'position' => 'header',
            'class' =>'widget\header\header',
            'published' => 1
        ),
        array(
            'name' => 'mail-search',
            'title' => 'Search Email',
            'position' => 'mail-search',
            'class' =>'app\mail\widget\search\search',
            'published' => 1
        ),
        array(
            'name' => 'notifications',
            'title' => 'Site Notifications',
            'position' => 'notifications',
            'class' =>'widget\notifications\notifications',
            'published' => 1
        )
		
    );
    
}



