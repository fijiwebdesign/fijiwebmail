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

namespace widget\header;

use Fiji\App\Widget;

/**
 * Generate HTML to display an Zend\Mail\AddressList list of emails
 */
class header extends Widget
{

    public function render($format = 'html')
    {
        ?>
<header>
    <!-- Main page logo -->
    <h1><a class="brand" href="?" title="Fiji Cloud Email">Fiji Cloud Email</a></h1>

    <!-- Main page headline -->
    <p>Open Source Cloud Email for everyone!</p>
</header>

    <?php
    }
}
