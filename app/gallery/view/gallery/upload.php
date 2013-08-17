<?php
/**
 * Gallery view for mealku
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
        <header class="messages-title">
            <h2><?php echo htmlentities($header, ENT_QUOTES, 'UTF-8'); ?></h2>
        </header>
        <section>
            
            <form id="upload-form" method="post" enctype="multipart/form-data" action="?">
                
                <label for="media">Select Media</label>
                
                <label for="title">Title</label>
                <input name="title" />
                
                <label for="description">Description</label>
                <textarea name="description"></textarea>
                
                <input type="file" name="media" />
                
                <select name="galleryId">
                    <?php foreach ($GalleryList as $Gallery) : ?>
                        <option value="<?php echo $Gallery->getId(); ?>"><?php echo $Gallery->title; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <button name="view" value="saveUpload">Upload</button>
                
                <input type="hidden" name="app" value="gallery" />
            
            </form>
            
        </section>
    </div>
</article>