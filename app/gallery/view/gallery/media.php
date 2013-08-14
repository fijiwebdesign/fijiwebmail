<?php
/**
 * Fiji Cloud Mail
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
?>


<style type="text/css">


</style>

<article class="data-block">
    <div class="data-container">
      
        <header class="title">
            <h2><?php echo htmlentities($Media->title, ENT_QUOTES, 'UTF-8'); ?></h2>
        </header>
        <section class="media">
            <img src="<?php echo htmlentities($Media->url); ?>" />
            <p><?php echo htmlentities($Media->description); ?></p>
        </section>
        
    </div>
</article>
