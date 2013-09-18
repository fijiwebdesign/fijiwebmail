<?php

namespace app\mail\model;

/**
 * Fiji Cloud Email
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */

use Fiji\Factory;

/**
 * Mail Label Model
 * The labels are custom IMAP flags. We can have a flag like "flag/color" saved in IMAP.
 * We parse parts to get our flag name and color
 */
class Label extends Flag
{
    
    const SEP = '/';
    
    /**
     * Label Color
     */
    public $color;
    
    /**
     * Get Emails in this folder
     */
    public function loadDataFromFlag($flag)
    {
        $flag = explode(self::SEP, $flag);
        if (isset($flag[0])) {
            $this->title = $flag[0];
        }
        if (isset($flag[1])) {
            $this->color = $flag[1];
        }
        return $this;
    }
    
    public function __toString()
    {
        return $this->title . ($this->color ? self::SEP . $this->color : '');
    }

}
