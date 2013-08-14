<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

//use Fiji\Mail\Storage\Imap;
use Zend\Mail\Storage\Imap;
use Fiji\Cache\File as Cache;
use Fiji\Factory;
use app\mail\view\widget\addressList as addressListWidget;

// our application control
$App = Factory::getSingleton('Fiji\App\Application');
$Doc = Factory::getSingleton('Fiji\App\Document');
$Req = Factory::getSingleton('Fiji\App\Request');

// we need a session
$User = Factory::getSingleton('Fiji\App\User');
if (!$User->isAuthenticated()) {
    // set our return path and redirect to login page
    $App->setReturnUrl('?app=mail');
    $App->redirect('?app=auth');
}

// configs @todo
$Config = Factory::getSingleton('config\Mail');

// user imap configs
$options = $User->imapOptions;
if (!$options) {
    $App->redirect('?app=auth');
}

$folder = $Req->getVar('folder', null);

$Imap = Factory::getSingleton('Fiji\Mail\Storage\Imap', array($options));
$Cache = new Cache();

$ImapHelper = new app\mail\helper\Imap($Imap);

// Zend Imap will select INBOX by default
if ($folder) {
    $Imap->selectFolder($folder);
}

$searchQuery = $Req->getVar('q');

$ids = $Imap->search(array('TEXT "' . htmlentities($searchQuery) . '"'));

$ids = array_reverse($ids ? $ids : array(), true);

// number of messages to show
$max = 20;

$c = 1;
$messages = array();
foreach($ids as $id) {
    
    // cahce the messages
    $cacheId = 'Imap::getMessage(msgId:' . $id . ',folder:' . $folder . ',$options:' . json_encode($options);
    if ($Cache->exists($cacheId) && $Config->get('cache_imap')) {
        $message = $Cache->get($cacheId);
    } else {
        $message = $ImapHelper->getMessageHeaders($id);
        if ($Config->get('cache_imap')) {
            $Cache->set($cacheId, $message);
        }
    }
    
    $message->fromWidget = new addressListWidget($message->getHeader('from')->getAddressList());
    $message->toWidget = new addressListWidget($message->getHeader('to')->getAddressList());
    
    $messages[] = $message;
    
    if ($c == $max) {
        break;
    }
    $c++;
}

?>

<style type="text/css">

.stats > li > p {
    margin-left: 5px;
    margin-right: 5px;
}
    
.subject {
    width: 55%;
    text-overflow: ellipsis;
    display: block;
    overflow: hidden;
    white-space: nowrap;
}

.from {
    width: 20%;
    font-weight: bold;
    text-overflow: ellipsis;
    display: block;
    overflow: hidden;
    white-space: nowrap;
}

.stats > li {
    cursor: pointer;
}

.stats > li.unseen {
    background-color: #fff;
}

.label {
    margin-right: 3px;
}

.messages-title {
    margin-bottom: 15px;
}

.message-header {
    overflow: hidden;
    margin-bottom: 15px;
}

.message-header .pagination {
    float: right;
    margin: 0;
}

.message-header .message-tools {
    float: left !important;
}

.message-header ul {
    padding: 1px;
}

.message-header ul > li a {
    border-radius: 0;
}
    
.message-header ul > li:first-child a {
    border-radius: 4px 0 0 4px;
}

.message-header ul > li:last-child a {
    border-radius: 0 4px 4px 0;
}

.stats li {
    cursor: pointer;
    margin-bottom: 0;
    border-bottom: none;
    border-radius: 0;
}

.stats li:first-child {
    border-radius: 4px 4px 0 0;
}

.stats li:last-child {
    border-radius: 0 0 4px 4px;
    margin-bottom: 6px;
    border-bottom: 1px solid #d9d9d9;
}

.stats li:hover {
    background-color: rgb(255, 250, 194);
}

.stats li .select {
    line-height: 25px;
}

.icon-star-empty {
    opacity: 0.25;
}

.icon-star-empty:hover {
    opacity: 0.45;
}

.date {
    text-overflow: ellipsis;
    overflow: hidden;
    width: 10%;
    text-align: right;
    white-space: nowrap;
    float: right !important;
}

.addressList {
    margin: 0 !important;
    padding: 0;
    list-style: none;
    display: inline-block;
}

.addressList a {
    display: inline-block;
}

</style>

<article class="data-block">
    <div class="data-container">
        <header class="messages-title">
            <h2>Search: <?php echo htmlentities($searchQuery); ?></h2>
            <?php echo $Doc->search; ?>
        </header>
        <section>
            
            <div class="message-header">
                <div class="message-tools pagination">
                    <ul>
                        <li><a href="#" title="Archive Messages"><span class="icon-download"></span></a></li>
                        <li><a href="#" title="Mark as Spam"><span class="icon-warning-sign"></span></a></li>
                        <li><a href="#" title="Delete Messages"><span class="icon-trash"></span></a></li>
                    </ul>
                </div>
                <div class="pagination">
                    <ul>
                        <li class="disabled"><a href="#" title="Newer Messages"><span class="icon-arrow-left"></span></a></li>
                        <li><a href="#" title="Older Messages"><span class="icon-arrow-right"></span></a></li>
                    </ul>
                </div>
            </div>
            
            <ul class="stats">
                
                <?php foreach($messages as $i => $message) : ?>
                <li class="<?php echo $message->seen ? 'seen' : 'unseen'; ?>" data-id="<?php echo $message->num; ?>">
                    <p class="select"><input type="checkbox"></p>
                    <p class="star"><span class="<?php echo $message->stared ? 'icon-star' : 'icon-star-empty'; ?>"></span></p>
                    <p class="from"><?php echo $message->fromWidget->toHtmlInline(); ?></p>
                    <p class="subject">
                        <?php
                        foreach($message->labels as $label) {
                            echo '<span class="label ' . $label[1] . '">' . $label[0] . '</span>';
                        }
                        echo $message->subject;
                        ?>
                    </p>
                    <p class="date"><?php echo date('M jS', strtotime($message->date)); ?></p>
                </li>
                <?php endforeach; ?>
                
            </ul>
        </section>
    </div>
</article>

<script type="text/javascript">
  
$(function() {
    $('.stats li').bind('click', function() {
        location = '?app=mail&page=message&id=' + $(this).attr('data-id');
    });
});
    
</script>