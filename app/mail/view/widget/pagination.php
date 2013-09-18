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

use Fiji\Factory;
use Fiji\App\Widget;

/**
 * Generate HTML to display the pagination
 */
class pagination extends Widget
{
    /**
     * @var Int Current Page (starts at 1, which is actually index of 0)
     */
    protected $page;
    /**
     * @var Int Items per page
     */
    protected $perPage;
    
    /**
     * @var Int Number of items
     */
    public $count;
	
	/**
	 * Url for prev page
	 */
	public $prevUrl;
	
	/**
	 * Url for next page
	 */
	public $nextUrl;
    
    public function __construct($page = 1, $perPage = 10, $count = 10)
    {
        $this->page = max($page, 1); // don't pass 0 as page
        $this->perPage = $perPage;
        $this->count = $count;
    }
	
	public function getPage()
	{
		return $this->page;
	}
	
	public function getPageCount()
	{
		return ceil($this->getCount()/$this->perPage);
	}
    
    public function getPageIndex()
    {
        return $this->page - 1;
    }
    
    public function getPrevPage()
    {
        return $this->page - 1;
    }
    
    public function getNextPage()
    {
        return $this->page + 1;
    }
    
    public function getStart()
    {
        $start = max($this->page * $this->perPage - $this->perPage, 0);
        return $start;
    }
    
    public function getEnd()
    {
        $end = min($this->page * $this->perPage, $this->count) - 1;
        return $end;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    public function hasNext()
    {
        return ($this->getPage() < $this->getPageCount());
    }
    
    public function hasPrev()
    {
        return ($this->getPage() > 1);
    }
    
    public function toHtml()
    {
        $folder = Factory::getRequest()->getVar('folder');
        $query =  Factory::getRequest()->getVar('q');
		
		// @todo fix
		$prevUrl = $this->prevUrl ? $this->prevUrl : '?app=mail&folder=' . $folder . '&p=' . $this->getPrevPage();
		$nextUrl = $this->nextUrl ? $this->nextUrl : '?app=mail&folder=' . $folder . '&p=' . $this->getNextPage();
        
        $html = '
        <div class="pagination-data"><span class="start">' . ($this->getStart()+1) . '</span><span class="to"> - </span><span class="end">' . ($this->getEnd()+1) . '</span> of <span class="count">' . $this->getCount() . '</span></div>
        <ul class="pagination-list" data-page="' . $this->getPage() . '">
            <li class="' . ($this->hasPrev() ? '' : 'disabled') . '">
                <a class="page-next" href="' . ($this->hasPrev() ? $prevUrl : 'javascript:;') . ($query ? ('&q=' . $query) : '') . '" title="Newer Messages">
                    <span class="awe-arrow-left"></span>
                </a>
            </li>
            <li class="' . ($this->hasNext() ? '' : 'disabled') . '">
                <a class="page-prev" href="' . ($this->hasNext() ? $nextUrl : 'javascript:;') . ($query ? ('&q=' . $query) : '') . '" title="Older Messages" data-tooltip-placement="left">
                    <span class="awe-arrow-right"></span>
                </a>
            </li>
        </ul>';
		
		$html .= "
		<script>
		jQuery(function() {
			// pagination navigation via keyboard
		    $(window).bind('keyup', function(event) {
		    	var keyCode = {
		    		left: 37,
		    		right: 39
		    	};
		    	if (event.keyCode == keyCode.left) {
		    		$('.pagination .page-next')[0].click();
		    	}
		    	if (event.keyCode == keyCode.right) {
		    		$('.pagination .page-prev')[0].click();
		    	}
		    });
		});
		</script>
		";
        
        return $html;
    }

	public function render($format = 'html')
	{
		echo $this->toHtml();
	}
    
    
}
