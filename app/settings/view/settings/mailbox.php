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

?>

<style type="text/css">
</style>

<article class="data-block">
    <div class="data-container">

    <header class="title">
        <h2 class="tab-title"><?php echo htmlentities($header); ?></h2>
    </header>

    <section class="tab-content">

    <fieldset>
    <legend><?php echo htmlentities($SettingsWidget->getDescription()); ?></legend>
    <?php $SettingsWidget->render(); ?>
    </fieldset>

    </section>

    </div>
</article>

<script type="text/javascript">
$(document).ready(function() {
    $('.edit-password').click();
});
</script>
