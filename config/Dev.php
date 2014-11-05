<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace config;

use Fiji\App\Config;

/**
 * Development mode configuration
 */
class Dev extends Config 
{
    /**
     * Email to send email debugging to
     */
    public $defaultEmail = 'gabe@fijiwebdesign.com';

    /**
     * Default from email
     */
    public $defaultFromEmail = 'dev@fijicloudmail.com';
    
}



