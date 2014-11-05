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

namespace app\settings\widget\ConfigProperty;

use Fiji\App\Widget;

/**
 * Generate HTML for configuration properties that are text elements 
 */
class Text extends Widget
{
    /**
     * @var app\settings\model\configProperty $Model  
     */
    protected $Model;

    public function __construct($Model)
    {
        $this->Model = $Model;
        parent::__construct($this->Model->type);
    }

    /**
     * Render the widget
     */
    public function render($format = 'html')
    {
        $value = htmlentities($this->Model->getValueJson(), ENT_QUOTES, 'utf-8');
        $name =  htmlentities($this->Model->name, ENT_QUOTES, 'utf-8');
        echo '
        <div class="control-group">
            <label class="control-label" for="input">' . $this->Model->title . '</label>
            <div class="controls">
                <input id="' . $name .  '" name="' . $name . '" class="input-xlarge" type="text" value="' . $value . '">
                <p class="help-block">' . $this->Model->description . '</p>
            </div>
        </div>';
    }
}
