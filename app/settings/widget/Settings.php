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

/**
 * Generate HTML for a Settings namespace (equates to a single config file)
 */
class Settings extends Widget
{

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

    public function renderForm()
    {
        echo '<form class="form-horizontal" method="post" action="?app=settings&view=save">';
        foreach($this->Model->Properties as $Property) {
            $className = 'app\\settings\\widget\\ConfigProperty\\' . ucfirst($Property->type);
            if (!class_exists($className)) {
                // @todo create all the Property type render's
                $className = 'app\\settings\\widget\\ConfigProperty\\Text';
            }
            $PropertyWidget = Factory::createWidget($className, array($Property));
            $PropertyWidget->render();
        }
        echo '<input type="hidden" name="namespace" value="' . $this->Model->namespace . '">';
        echo '<button type="submit" class="btn btn-primary" name="save">Save</button>';
        echo '</form>';
    }

    /**
     * Render the widget
     */
    public function render($format = 'html')
    {
        echo '<h2>' . $this->getTitle() . '</h2>';
        echo '<p>' . $this->getDescription() . '</p>';
        $this->renderForm();
    }
}
