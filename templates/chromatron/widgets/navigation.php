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
$Doc = Factory::getSingleton('Fiji\App\Document');

?>

<nav class="main-navigation nav-collapse" role="navigation">
    <ul>
        <li>
            <a href="?app=mail" class="no-submenu"><span class="fam-picture"></span>Inbox<span class="badge" title="5 new messages">5</span></a>
        </li>
        <li><a href="?app=mail&folder=[Gmail]/Sent Mail" class="no-submenu"><span class="fam-briefcase"></span>Sent Mail</a></li>
        <li><a href="?app=calendar" class="no-submenu"><span class="fam-calendar-view-day"></span>Calendar<span class="badge" title="27 tasks this week">27</span></a></li>
    </ul>
</nav>

<section class="side-note" id="side-note-compose">
    <p><a class="btn btn-primary btn-compose" href="?app=mail&page=message&view=compose"><span class="icon-pencil"></span> Compose</a></p>
</section>

<section class="side-note">
    <p><?php echo $Doc->folderListWidget->toHtml(); ?></p>
</section>

<style>
   
#side-note-compose {
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
