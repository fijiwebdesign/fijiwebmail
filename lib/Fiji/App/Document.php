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

/**
 * HTML Document sent back client
 */
class Document {
    
    public $title;
    
    public $content; 
    
    public $navigation;
    
    public function __construct() {}
    
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
    public function renderWidget(Model\Widget $WidgetModel)
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
    
    
}
