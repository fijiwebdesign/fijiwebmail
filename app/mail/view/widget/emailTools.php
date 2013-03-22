<?php
/**
 * Fiji Mail Server 
 *
 * @author    gabe@fijiwebdesign.com
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

 
namespace app\mail\view\widget;

/**
 * Generate HTML to display the email tools list (read/unread/star etc.)
 */
class emailTools
{
    /**
     * @var String HTMLElement Id
     */
    protected $id;
    
    /**
     * @var Int Number of items
     */
    public $count;

    public function __construct($id = 'email-tools', $title = 'More', $links = array())
    {
        $this->id = $id;
        $this->title = $title;
        $this->links = $links;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Add a link to the Tools
     */
    public function addLink($value, $name)
    {
        $this->links[] = (object) array('name' => $name, 'value' => $value);
    }
    
    /**
     * Retrieve the HTML
     */
    public function toHtml()
    {
        $html = '';
        foreach ($this->links as $i => $link) {
            $html .= '<li><a href="#" class="' . htmlentities($this->id . '-' . $link->value) . '" data-id="' . htmlentities($link->value) . '">' . htmlspecialchars($link->name) . '</a></li>';
        }
        
        $html = '<div class="btn-group" id="' . htmlentities($this->id) . '">
            <button class="btn" data-toggle="dropdown">' . $this->title . '</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                ' . $html . '
            </ul>
        </div>';
        
        return $html;
    }
    
}
