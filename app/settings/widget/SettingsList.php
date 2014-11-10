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

namespace app\settings\widget;

use Fiji\App\Widget;
use Fiji\Factory;
use Fiji\App\Model;
use Fiji\App\ModelCollection as Collection;

/**
 * Generate HTML for a Settings namespace (equates to a single config file)
 */
class SettingsList extends Widget
{

    public function __construct(Model $Model, Collection $Collection)
    {
        $this->Model = $Model;
        $this->Collection = $Collection;
        parent::__construct($Model->title);
    }

    public function getTitle()
    {
        return $this->Model->title;
    }

    public function getDescription()
    {
        return $this->Model->description;
    }

    public function getNamespace()
    {
        return str_replace("\\", '_', $this->Model->namespace);
    }

    /**
     * Render the Collection as a list
     * @param Fiji\App\ModelCollection
     */
    public function render($format = 'html')
    {
        echo '<table class="table table-striped">';
        // table headers
        echo '<thead>';
        $this->renderTableHeader();
        echo '</thead>';
        // table data
        echo '<tbody>';
        $this->renderTableData();
        echo '</tbody>';
        echo '</table>';
        echo '<p>';

        // action links
        $links = isset($this->Model->links) ? $this->Model->links : array();
        $addLink = isset($links['add'][1]) ? $links['add'][1] : '?app=settings&view=add';
        $addText = isset($links['add'][0]) ? $links['add'][0] : 'Add';

        echo '<a class="btn btn-primary" id="add-' . $this->getNamespace() . '" href="' . $addLink . '">' . $addText . '</a>';
    }

    /**
     * Table Header
     */
    public function renderTableHeader()
    {
        foreach($this->Model->Properties as $Property) {
            echo '<th>' . $Property->title . '</th>';
        }
        echo '<th>&nbsp;</th>';
    }

    /**
     * Table body
     */
    public function renderTableData()
    {
        // links
        $links = isset($this->Model->links) ? $this->Model->links : array();
        $defaultLink = isset($links['default'][1]) ? $links['default'][1] : '?app=settings&view=default&id={id}';
        $defaultText = isset($links['default'][0]) ? $links['default'][0] : 'Edit';
        $editLink = isset($links['edit'][1]) ? $links['edit'][1] : '?app=settings&view=edit&id={id}';
        $editText = isset($links['edit'][0]) ? $links['edit'][0] : 'Edit';
        $deleteLink = isset($links['delete'][1]) ? $links['delete'][1] : '?app=settings&view=delete&id={id}';
        $deleteText = isset($links['delete'][0]) ? $links['delete'][0] : 'Edit';

        // build table body from collection, model and property data
        foreach($this->Collection as $Model) {
            echo '<tr>';
            foreach($this->Model->Properties as $Property) {
                // hide passwords
                if ($Property->type == 'password') {
                    $Model->{$Property->name} = '******';
                }
                echo '<td>' . $Model->{$Property->name} . '</td>';
            }
            echo '<td class="toolbar">
				<div class="btn-group">
					<a class="btn btn-flat" title="Make Default" href="' . str_replace('{id}', $Model->id, $defaultLink) . '"><span class="awe-pushpin"></span></a>
					<a class="btn btn-flat" title="Edit" href="' . str_replace('{id}', $Model->id, $editLink) . '"><span class="awe-wrench"></span></a>
					<a class="btn btn-flat" title="Delete" href="' . str_replace('{id}', $Model->id, $deleteLink) . '"><span class="awe-remove"></span></a>
				</div>
			</td>';
            echo '</tr>';
        }
    }
}
