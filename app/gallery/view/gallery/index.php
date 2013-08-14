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

.medias {
    clear: both;
    overflow: hidden;
}

.media {
    float: left;
    display: block;
    margin-right: 20px;
}

.media img {
    max-width: 300px;
}

</style>

<h1><?php echo $header; ?></h1>
<article class="data-block">
    <div class="data-container">
      
    <?php foreach($GalleryList as $Gallery) : ?>
      
        <header class="title">
            <h2>
                <a href="?app=gallery&view=gallery&id=<?php echo $Gallery->getId(); ?>">
                <?php echo htmlentities($Gallery->title, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </h2>
        </header>
        <section>
            <ul class="medias">
            <?php foreach($Gallery->getMedia() as $Media) : ?>
                <li class="media">
                    <a href="?app=gallery&view=media&id=<?php echo $Media->getId(); ?>">
                        <h3><?php echo htmlentities($Media->title); ?></h3>
                        <img src="<?php echo htmlentities($Media->url); ?>" />
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </section>
        
    <?php endforeach; ?>
        
    </div>
</article>
