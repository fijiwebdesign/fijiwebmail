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

 
namespace app\mail\view\widget\attachment;

/**
 * Generate HTML to upload attachments
 */
class attachment extends \Fiji\App\Widget
{
    protected $model;
    
    public function __construct($model = null)
    {
        parent::__construct($model);
    }
    
    public function render($format = 'html')
    {
        include(__DIR__ . '/view/uploader.php');
    }
    
    public function getUploadDirPath()
    {
        return __DIR__ . '/uploads';
    }
}
