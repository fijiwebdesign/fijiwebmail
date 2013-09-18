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

$folderListWidget = new app\mail\view\widget\folderList('folder-list');

$app = $Req->get('app', 'mail');
$folder = $Req->get('folder', 'inbox');

// add the menu items @todo move to configuration or service
$this->addMenuItem('Inbox', '?app=mail', 'awe-envelope', '', $folder == 'inbox');
$this->addMenuItem('Sent Mail', '?app=mail&folder=Sent Mail', 'awe-plane', '', $folder == 'Sent Mail');
$this->addMenuItem('Drafts', '?app=mail&folder=Drafts', 'awe-edit', '', $folder == 'Drafts');
$this->addMenuItem('Archive', '?app=mail&folder=Archive', 'awe-briefcase', '', $folder == 'Archive');
$this->addMenuItem('Trash', '?app=mail&folder=Trash', 'awe-trash', '', $folder == 'Trash');
$this->addMenuItem('Spam', '?app=mail&folder=Spam', 'awe-warning-sign', '', $folder == 'Spam');

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

<?php if ($app == 'mail') : ?>
<section class="side-note side-note-compose" id="side-note-compose-email">
	<p>
	<div class="btn-group">
        <a class="btn btn-primary btn-compose" href="?app=mail&page=message&view=compose"><span class="awe-pencil"></span> Compose</a>
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="javascript:showComposeEmailModal('?app=mail&page=message&view=compose')">Quick Compose</a>
            <li><a href="?app=mail&page=message&view=compose">Full Compose</a>
        </ul>
    </div>
    </p>
</section>

<section class="side-note side-note-compose" id="side-note-folderlist">
    <p><?php echo $folderListWidget->toHtml(); ?></p>
</section>

<script>
$(function() {
   $('#<?php echo htmlentities($folderListWidget->id); ?>').bind('change', function() {
      location = '?app=mail&folder=' + this.value; 
   });
});
</script>
<?php endif; ?>

<?php if ($app == 'calendar') : ?>
	
<section class="side-note side-note-compose" id="side-note-compose-event">
    <p>
    <div class="btn-group">
        <a class="btn btn-primary btn-compose" href="?app=calendar&view=editEvent"><span class="awe-pencil"></span> Create Event</a>
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="javascript:showEventDetails('?app=calendar&view=editEvent')">Quick Compose</a>
            <li><a href="?app=calendar&view=editEvent">Full Compose</a>
        </ul>
    </div>
    </p>
</section>

<?php endif; ?>
