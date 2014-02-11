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

namespace widget\navigation;

use Fiji\Factory;
use Fiji\App\Widget;

/**
 * Generate HTML to display a navigation menu
 * @todo create Menu Model. Navigation widget then creates Nav by Menu Model search
 * @todo incorporate Badges
 */
class navigation extends Widget
{
    protected $model;
    
    protected $items;
    
    public function __construct($model)
    {
        parent::__construct($model);
    }
    
    public function render($format = 'html')
    {
        include(__DIR__ . '/view/navigation.php');
    }
    
    public function addMenuItem($text, $link, $icon = '', $className = '', $current = false, $Badge = false)
    {
        if ($current) {
            $className .= ' current';
        }
        $this->items[] = Factory::createModel(
        	'widget\navigation\model\MenuItem',
        	array(array('text' => $text, 'link' => $link, 'icon' => $icon, 'className' => $className))
		);
    }
    
    public function getMenuItems()
    {
        return $this->items;
    }
	
	function renderBadge(MenuItemBadge $Badge)
	{
		return '<span class="badge" title="' . htmlentities($Badge->title) . '">' . htmlentities($Badge->text) . '</span>';
	}
}
