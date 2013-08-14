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

 
namespace app\mail\view\widget\navigation;

/**
 * Generate HTML to display an Zend\Mail\AddressList list of emails
 */
class navigation extends \Fiji\App\Widget
{
    protected $model;
    
    public function __construct($model)
    {
        parent::__construct($model);
    }
    
    public function render($format = 'html')
    {
        include(__DIR__ . '/view/navigation.php');
    }
}
