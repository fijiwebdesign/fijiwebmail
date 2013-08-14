<?php

namespace service\DataProvider;

/**
 * Data Provider interface
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_App
 */


 /**
 * Data provider exception
 */
class Exception extends \Exception {
    public function __construct($msg)
    {
        parent::__construct($msg);
    }
}