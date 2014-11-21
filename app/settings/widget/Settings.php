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
use app\settings\model\Settings as Model;
use Fiji\App\ModelCollection as Collection;

/**
 * Generate HTML for a Settings namespace (equates to a single config file)
 */
class Settings extends Widget
{

    /**
     * @var app\settings\model\Settings $Model Settings Model
     *          contains the Configuration Model as $Model->Model of type Fiji\Service\DomainObject
     *          contains each Configuration Model Property as $Model->Properties of type Collection[app\settings\model\ConfigProperty]
     *          each $Model->Properties[] is loaded from ReflectionProperty with doc block comments data
     */
    public $Model;

    /**
     * Builds our widget
     *
     * @param app\settings\model\Settings $Model Settings Model
     */
    public function __construct(Model $Model)
    {
        $this->Model = $Model;
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
     * Render the Model into a form
     */
    public function renderForm()
    {
        // action links
        $links = isset($this->Model->links) ? $this->Model->links : array();
        $saveLink = isset($links['save'][1]) ? $links['save'][1] : '?app=settings&view=save';
        $saveText = isset($links['save'][0]) ? $links['save'][0] : 'Save';

        echo '<form class="form-horizontal" method="post" action="' . $saveLink . '">';
        $this->renderFormElements();
        echo '<button type="submit" class="btn btn-primary btn-large" name="save">' . $saveText . '</button>';
        echo '</form>';
    }

    /**
     * Render the Model Properties to form elements
     */
    public function renderFormElements()
    {
        // each Property is a Reflection Property of the Config Model properties
        foreach($this->Model->Properties as $Property) {
            $className = 'app\\settings\\widget\\ConfigProperty\\' . ucfirst($Property->type);
            if (!class_exists($className)) {
                // @todo create all the Property type render's
                $className = 'app\\settings\\widget\\ConfigProperty\\Text';
            }
            $PropertyWidget = Factory::createWidget($className, array($Property));
            $PropertyWidget->render();
        }
        // Configuration Model ID if it exists so as to edit this Model
        if ($id = $this->Model->getConfigModel()->id) {
            echo '<input type="hidden" name="' . $this->Model->getConfigModel()->getIdKey() . '" value="' . intval($id) . '">';
        }
        // Config Model namespace that is being edited/saved
        echo '<input type="hidden" name="namespace" value="' . htmlentities($this->Model->namespace, ENT_QUOTES, 'utf-8') . '">';
    }

    /**
     * Render the widget
     */
    public function render($format = 'html')
    {
        $this->renderForm();
    }
}
