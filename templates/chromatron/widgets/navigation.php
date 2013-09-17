<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
use Fiji\Factory;

// Widgets are attached to doc. We need the folderList widget
$Doc = Factory::getDocument();

$GalleryList = Factory::createModelCollection('app\gallery\model\Gallery');
$GalleryList->find();

?>

<nav class="main-navigation nav-collapse" role="navigation">
    <ul>
        <li>
            <a href="?" class="no-submenu">
                <span class="fam-picture"></span>
                Home
            </a>
        </li>
    </ul>
</nav>

<nav class="main-navigation nav-collapse" role="navigation">
    <h3>Galleries</h3>
    <ul>
        <?php foreach($GalleryList as $Gallery) : ?>
        <li>
            <a href="?app=gallery&amp;view=gallery&amp;id=<?php echo $Gallery->getId(); ?>" class="no-submenu">
                <span class="fam-picture"></span>
                <?php echo htmlentities($Gallery->title); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<section class="upload">
    <ul>
        <li>
            <a href="?app=gallery&amp;view=upload" class="no-submenu">
                <span class="fam-picture"></span>
                Upload Images
            </a>
        </li>
    </ul>
</section>
