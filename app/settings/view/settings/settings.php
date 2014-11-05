<?php
/**
 * Gallery index view for mealku
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
            <h2><?php echo htmlentities($Gallery->title, ENT_QUOTES, 'UTF-8'); ?></h2>
        </header>
        <section>
            <ul class="medias">
            <?php foreach($Gallery->getMedia() as $Media) : ?>
            <li>
                <a href="?app=gallery&view=media&id=<?php echo $Media->getId(); ?>">
                    <h3><?php echo htmlentities($Media->title); ?></h3>
                    <img src="<?php echo htmlentities($Media->url); ?>" />
                </a>
            </li>
            <?php endforeach; ?>
            </ul>
        </section>
        
    </div>
</article>
