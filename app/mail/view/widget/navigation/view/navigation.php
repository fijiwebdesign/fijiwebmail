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

$Doc->folderListWidget = new app\mail\view\widget\folderList('folder-list');

$app = $Req->get('app', 'mail');
$folder = $Req->get('folder', 'inbox');

// add the menu items @todo move to configuration or service
$this->addMenuItem('Inbox', '?app=mail', 'icon-envelope', '', $folder == 'inbox');
$this->addMenuItem('Sent Mail', '?app=mail&folder=Sent Mail', 'icon-plane', '', $folder == 'Sent Mail');
$this->addMenuItem('Drafts', '?app=mail&folder=Drafts', 'icon-edit', '', $folder == 'Drafts');
$this->addMenuItem('Archive', '?app=mail&folder=Archive', 'icon-briefcase', '', $folder == 'Archive');
$this->addMenuItem('Trash', '?app=mail&folder=Trash', 'icon-trash', '', $folder == 'Trash');
$this->addMenuItem('Spam', '?app=mail&folder=Spam', 'icon-warning-sign', '', $folder == 'Spam');

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
            <a href="?app=calendar" class="no-submenu"><span class="fam-calendar-view-day"></span>Calendar<span class="badge" title="10 events this week">10</span></a>
        </li>
    </ul>
</nav>

<section class="side-note" id="side-note-compose">
    <p><a class="btn btn-primary btn-compose" href="?app=mail&page=message&view=compose"><span class="icon-pencil"></span> Compose</a></p>
</section>

<section class="side-note" id="side-note-folderlist">
    <p><?php echo $Doc->folderListWidget->toHtml(); ?></p>
</section>

<style>
   
#side-note-compose, #side-note-folderlist {
    text-align: center;    
}

#side-note-compose p .btn-compose {
    margin: auto;

</style>

<script>
$(function() {
   $('#<?php echo htmlentities($Doc->folderListWidget->id); ?>').bind('change', function() {
      location = '?app=mail&folder=' + this.value; 
   });
});
</script>
