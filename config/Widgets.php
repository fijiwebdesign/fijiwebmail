<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config;

/**
 * Widgets configuration
 * 
 * name: name of widget (used as className in HTML/JSON/XML rendering)
 * title: Title of widget (displayed on page)
 * position: where widget will be loaded
 * class: class called to render the widget
 * published: published state (1 = published, 0 = unpublished)
 */
class Widgets extends \Fiji\App\Config
{
    /**
     * Mail navigation widget
     */
    public $mail_nav = array(
        'name' => 'mail_nav',
        'title' => 'Navigation',
        'position' => 'navigation',
        'class' =>'app/mail/view/widget/navigation/navigation',
        'published' => 1
    );
    
}



