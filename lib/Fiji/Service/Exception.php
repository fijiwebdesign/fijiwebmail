<?php

namespace Fiji\Service;

/**
 * Service
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji\Service
 */


 /**
 * Service Exception
 */
class Exception extends \Exception {
    public function __construct($msg)
    {
        parent::__construct($msg);
    }
}