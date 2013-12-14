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

namespace app\mail\view\widget;

use Zend\Mail\AddressList as ZendAddressList;

/**
 * Generate HTML to display an Zend\Mail\AddressList list of emails
 */
class addressList
{
    protected $addressList; 
    
    public function __construct(ZendAddressList $addressList)
    {
        $this->addressList = $addressList;
        return $this;
    }
    
    public function toHtml()
    {
        $this->addressList->rewind();
        $html = '<ul class="addressList">';
        while($this->addressList->valid()) {
            $name = htmlentities(trim($this->addressList->current()->getName(), ' "'), ENT_QUOTES, 'UTF-8');
            $email = htmlentities(trim($this->addressList->current()->getEmail()), ENT_QUOTES, 'UTF-8');
            $html .= '<li><a href="#" title="' . $email . '">' . ($name ? $name : $email) . '</a></li>';
            $this->addressList->next();
        }
        $html .= '</ul>';
        return $html;
    }
    
    public function toHtmlInline()
    {
        $this->addressList->rewind();
        $html = '<span class="addressList">';
        while($this->addressList->valid()) {
            $name = htmlentities(trim($this->addressList->current()->getName(), ' "'), ENT_QUOTES, 'UTF-8');
            $email = htmlentities(trim($this->addressList->current()->getEmail()), ENT_QUOTES, 'UTF-8');
            $html .= '<a href="#" title="' . $email . '">' . ($name ? $name : $email) . '</a>';
            $this->addressList->next();
        }
        $html .= '</span>';
        return $html;
    }
    
}
