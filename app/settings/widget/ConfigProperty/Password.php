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
 * Generate HTML for configuration properties that are password elements
 */
class Password extends Widget
{
    /**
     * @var app\settings\model\configProperty $Model
     */
    protected $Model;

    public function __construct($Model)
    {
        $this->Model = $Model;
        parent::__construct($this->Model->type);

        self::renderScript();
    }

    public function renderScript()
    {
        static $rendered = false;
        if (!$rendered) {
            $rendered = true;
            echo '
            <script>
                $(function() {
                    $(\'.widget-password .edit-password\').on(\'click\',
                        function(e) {
                            e.preventDefault();
                            $($(this).data(\'target\')).attr(\'disabled\', false);
                            $(this).hide();
                        });
                });
            </script>
            ';
        }
    }

    /**
     * Render the widget
     */
    public function render($format = 'html')
    {
        $value = $this->Model->getValueJson();
        $value = htmlentities($value == 'null' ? '' : $value, ENT_QUOTES, 'utf-8');
        $name =  htmlentities($this->Model->name, ENT_QUOTES, 'utf-8');
        $description = htmlentities($this->Model->description, ENT_QUOTES, 'utf-8');
        $namespace = $this->Model->getObjectName();
        $id = htmlentities($name . crc32(uniqid(rand(), true)), ENT_QUOTES, 'utf-8');
        echo '
        <div class="widget-password control-group ' . $namespace . '">
            <label class="control-label" for="input">' . $this->Model->title . '</label>
            <div class="controls">
            <div class="input-group">
                <input id="' . $id .  '" name="' . $name . '" class="input-xlarge form-control" type="password" disabled>
                <span class="input-group-addon">
                  <a class="btn edit-password" data-target="#' . $id . '">change</a>
               </span>
            </div>
                <p class="help-block">' . $description . '</p>
            </div>
        </div>';
    }
}
