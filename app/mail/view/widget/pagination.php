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

use \Fiji\Factory;

/**
 * Generate HTML to display the pagination
 */
class pagination
{
    /**
     * @var Int Current Page
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
    
    public function __construct($page = 0, $perPage = 10, $count = 10)
    {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->count = $count;
    }
    
    public function getPage()
    {
        return $this->page;
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
        $start = max(($this->page-1) * $this->perPage, 0);
        return $start;
    }
    
    public function getEnd()
    {
        $end = min(max($this->page, 1) * $this->perPage, $this->count) - 1;
        return $end;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    public function hasNext()
    {
        return ($this->getEnd() < $this->count-1);
    }
    
    public function hasPrev()
    {
        return ($this->getStart() > 0);
    }
    
    public function toHtml()
    {
        $folder = Factory::getSingleton('Fiji\App\Request')->getVar('folder');
        $query =  Factory::getSingleton('Fiji\App\Request')->getVar('q');
        
        $html = '
        <div class="pagination-data"><span class="start">' . ($this->getStart()+1) . '</span> - <span class="end">' . ($this->getEnd()+1) . '</span> of <span class="count">' . $this->getCount() . '</span></div>
        <ul class="pagination-list" data-page="' . $this->getPage() . '">
            <li class="' . ($this->hasPrev() ? '' : 'disabled') . '">
                <a class="page-next" href="' . ($this->hasPrev() ? '?app=mail&folder=' . $folder . '&p=' . $this->getPrevPage() : '#') . ($query ? ('&q=' . $query) : '') . '" title="Newer Messages">
                    <span class="icon-arrow-left"></span>
                </a>
            </li>
            <li class="' . ($this->hasNext() ? '' : 'disabled') . '">
                <a class="page-prev" href="' . ($this->hasNext() ? '?app=mail&folder=' . $folder . '&p=' . $this->getNextPage() : '#') . ($query ? ('&q=' . $query) : '') . '" title="Older Messages">
                    <span class="icon-arrow-right"></span>
                </a>
            </li>
        </ul>';
        
        return $html;
    }
    
    
}
