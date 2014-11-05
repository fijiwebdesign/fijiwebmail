<?php
/**
 * Settings view
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Fiji\Factory;

$SettingsWidgets = array();
foreach($SettingsCollection as $Settings) {
    $SettingsWidgets[] = Factory::getWidget('app\settings\widget\Settings', array($Settings));
}

?>

<style type="text/css">



</style>

<article class="data-block">
    <div class="data-container">

    <header class="title">
        <h2 class="tab-title"><?php echo htmlentities($header); ?></h2>
        <ul class="data-header-actions tabs">
            <?php foreach($SettingsWidgets as $i => $SettingsWidget) : ?>
            <li class="settings-tab<?php echo $i == 0 ? ' active' : ''; ?>">
                <a href="#<?php echo htmlentities($SettingsWidget->getNamespace()); ?>"><?php echo htmlentities($SettingsWidget->getTitle()); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </header>

    <section class="tab-content">
      
    <?php foreach($SettingsWidgets as $SettingsWidget) : ?>
        <div class="tab-pane" id="<?php echo htmlentities($SettingsWidget->getNamespace()); ?>">
            <fieldset>
            <legend><?php echo htmlentities($SettingsWidget->getDescription()); ?></legend>
            <?php $SettingsWidget->renderForm(); ?>
            </fieldset>
        </div>
    <?php endforeach; ?>

    </section>
        
    </div>
</article>

<script>
$(function() {
    // Tabs
    $('.settings-tab a').click(function (e) {
        e.preventDefault();
        //$(this).tab('show'); // not working for some reason
        var href = $(this).attr('href').replace('#', '');
        $('.settings-tab').removeClass('active');
        $('.tab-pane').hide();
        $('#' + href).show();
        $(this).parent().addClass('active');
        $('.tab-title').html($(this).html());
    });
    // trigger active tab
    $('.tabs .active > a ').trigger('click');
});
</script>
