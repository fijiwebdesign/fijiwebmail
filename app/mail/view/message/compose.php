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
        <h2>Compose email</h2>
    </header>
    <div id="form-error" class="alert alert-info alert-block fade in">
        <button class="close">×</button>
        <span id="form-error-body"></span>
    </div>
    <section>
        <?php echo $composeForm->toHtml(); ?>
    </section>
</article>

<style>

.wysihtml5-sandbox {
    height: 250px !important;
}

#form-error {
	display: none;
}

.control-group.error .wysihtml5-sandbox {
	border-color: #b94a48 !important;
}
    
</style>
