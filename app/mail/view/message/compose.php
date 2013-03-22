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

$composeForm = new app\mail\view\widget\composeForm();

?>

<article class="data-block">
    <header>
        <h2>New email</h2>
        <a id="compose-send" class="btn btn-alt btn-primary" type="submit">Send</a>
    </header>
    <section>
        <?php echo $composeForm->toHtml(); ?>
    </section>
</article>

<style>

.wysihtml5-sandbox {
    height: 280px !important;
}
    
</style>
