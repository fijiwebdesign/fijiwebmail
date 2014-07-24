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
$Req = Factory::getRequest();
$Config = Factory::getConfig('config\Mail');

$DefaultFolders = Factory::getSingleton('data\Folders');

$app = $Req->get('app', 'mail');
$folder = $Req->get('folder', 'inbox');

foreach($DefaultFolders as $DefaultFolder) {
    $def_name = $DefaultFolder->def_name;
    $name = $DefaultFolder->name;
    $title = $DefaultFolder->title;
    $icon = $DefaultFolder->icon;
    $this->addMenuItem($title, '?app=mail&folder=' . $name, $icon, '', $folder == $name);
}

?>

<nav class="main-navigation nav-collapse" role="navigation">
    <ul>
        <li<?php if ($app == 'mail') : echo ' class="current"'; endif; ?>>
            <a href="?app=mail" class="no-submenu"><span class="fam-email"></span>Mail</a>
            <ul>
                <?php foreach($this->getMenuItems() as $item) {
                    echo '<li><a href="' . $item->link . '" class="no-submenu ' . $item->className . '"><i class="' . $item->icon . '"></i>&nbsp;' . $item->text . '</a></li>';
                }
                ?>
            </ul>
        </li>
        <li<?php if ($app == 'calendar') : echo ' class="current"'; endif; ?>>
            <a href="?app=calendar" class="no-submenu"><span class="fam-calendar-view-day"></span>Calendar</a>
        </li>
    </ul>
</nav>