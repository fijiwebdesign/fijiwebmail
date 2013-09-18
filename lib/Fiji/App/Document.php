<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\App;

use Fiji\Factory;
use Fiji\App\Model\Widget;

/**
 * HTML Document sent back client
 */
class Document {
    
    public $title;
	
	/**
	 * @var widget\documentHead
	 */
	protected $head;
    
    public $content; 
    
    public $navigation;
    
    public function __construct() {
    	
		unset($this->head); // get $this->head dynamically through $this->getHead()
    }
	
	/**
	 * Retrieve $this->head dynamically
	 */
	public function getHead()
	{
		return Factory::getWidget('documentHead');
	}
    
    /**
     * Renders widgets published in the specified position
     * @param String Position defined in the site template
     */
    public function renderWidgets($position)
    {
        $Widgets = $this->getWidgets($position);
        foreach($Widgets as $Widget) {
            $this->renderWidget($Widget);
        }
    }
    
    /**
     * Render a single widget given the model
     * The model contains the options/parameters, name, title etc. of widget
     * @param Fiji\App\Model\Widget Model representing settings of widget
     */
    public function renderWidget(Widget $WidgetModel)
    {
        $Widget = Factory::getSingleton($WidgetModel->class, array($WidgetModel));
        $Widget->render('html');
    }
    
    /**
     * Loads widgets from data\Widgets data collection
     * @todo Load widgets from a data provider
     */
    public function getWidgets($position)
    {
        $WidgetsCollection = Factory::getSingleton('data\Widgets');
        $Widgets = array();
        foreach($WidgetsCollection as $WidgetModel) {
            if ($WidgetModel->position == $position) {
                $Widgets[] = $WidgetModel;
            }
        }
        return $Widgets;
    }
    
    /**
     * @todo Allow loading widgets from defined data sources
     */
    public function setWidgetsDataProvider()
    {
        
    }
	
	/**
     * Custom property getters 
     * @example retrieving $this->foo maps to $this->getFoo()
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (is_callable(array($this, $method))) {
            return $this->$method();
        }
        return $this->$name;
    }
    
    /**
     * Custom property setters 
     * @example setting $this->foo = $bar maps to $this->setFoo($bar)
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (is_callable(array($this, $method))) {
            return $this->$method($value);
        }
        return $this->$name = $value;
    }
    
    /**
     * Custom method calls
     */
    public function __call($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
    }
    
    
}
