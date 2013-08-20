<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
$SearchWidget = '';
?>

<article class="data-block">
    <div class="data-container">
        <header class="messages-title">
            <h2><?php echo htmlentities($header, ENT_QUOTES, 'UTF-8'); ?></h2>
            <?php echo $SearchWidget; ?>
        </header>
        <section>
            
            <div class="message-header">
                <div class="tools-selector">
                    <?php echo $selectWidget->toHtml(); ?>
                </div>
                <div class="tools-default pagination">
                    <ul>
                        <li><a class="btn-archive" href="#" title="Archive Messages"><span class="icon-download"></span></a></li>
                        <li><a class="btn-spam" href="#" title="Mark as Spam"><span class="icon-warning-sign"></span></a></li>
                        <li><a class="btn-delete" href="#" title="Delete Messages"><span class="icon-trash"></span></a></li>
                    </ul>
                </div>
                <div class="tools-folder-list-move">
                    <?php echo $folderListWidget->toHtml2(); ?>
                </div>
                <div class="tools-more">
                    <?php echo $toolsWidget->toHtml(); ?>
                </div>
                <div class="mail-pagination pagination">
                    <?php echo $paginationWidget->toHtml(); ?>
                </div>
            </div>
            
            <form id="mailbox-form">
            <ul class="stats mailbox" data-folder="<?php echo htmlentities($this->folder); ?>">
                
                <?php foreach($messages as $i => $message) : ?>
                    
                <?php
                
                    $date = date('M jS', strtotime($message->date));
                    if ($date == date('M jS')) {
                        $date = date('g:i a', strtotime($message->date));
                    }
                    
                    $messageClass = array('msg');
                    $messageClass[] = $message->seen ? 'seen' : 'unseen';
                    $messageClass[] = $message->flagged ? 'flagged' : 'unflagged';
                    $messageClass = implode(' ', $messageClass);
                
                ?>
                    
                <li class="<?php echo $messageClass; ?>" data-uid="<?php echo intval($message->uid); ?>">
                    <p class="select"><input class="mail-checkbox" type="checkbox" name="uids[]" value="<?php echo intval($message->uid); ?>"></p>
                    <p class="star"><span class="<?php echo $message->flagged ? 'icon-star' : 'icon-star-empty'; ?>"></span></p>
                    <?php if ($this->folder == $sentFolder) : ?>
                        <p class="to">To: <?php echo $message->toWidget->toHtmlInline(); ?></p>
                    <?php else: ?>
                        <p class="from"><?php echo $message->fromWidget->toHtmlInline(); ?></p>
                    <?php endif; ?>
                    <p class="subject">
                        <?php
                        foreach($message->labels as $label) {
                            echo '<span class="label ' . $label[1] . '">' . $label[0] . '</span>';
                        }
                        echo $message->subject;
                        ?>
                    </p>
                    <p class="date"><?php echo htmlentities($date); ?></p>
                </li>
                <?php endforeach; ?>
                
            </ul>
            </form>
            
        </section>
    </div>
</article>

<script src="public/js/jquery.json.js"></script>
<script type="text/javascript">
  
$(function() {
    
    // initalization
    handleMailSelections();
    
    $('.mailbox li').bind('click', function(event) {
        location = '?app=mail&page=message&uid=' + $(this).attr('data-uid')
                + '&folder=' + $('.mailbox').attr('data-folder');
    });
    
    $('.btn-delete').bind('click', function(event) {
        event.preventDefault();
        if (!mailCheckboxHasSelections()) return;
        location = '?app=mail&page=message&view=move&to=Trash&folder=' + $('.mailbox').attr('data-folder') + '&' + $('#mailbox-form').serialize();        
    });
    
    $('.btn-spam').bind('click', function(event) {
        event.preventDefault();
        if (!mailCheckboxHasSelections()) return;
        location = '?app=mail&page=message&view=move&to=Spam&folder=' + $('.mailbox').attr('data-folder') + '&' + $('#mailbox-form').serialize();        
    });
    
    $('.btn-archive').bind('click', function(event) {
        event.preventDefault();
        if (!mailCheckboxHasSelections()) return;
        location = '?app=mail&page=message&view=move&to=Archive&folder=' + $('.mailbox').attr('data-folder') + '&' + $('#mailbox-form').serialize();        
    });
    
    // dropdown list
    $('#<?php echo htmlentities($folderListWidget->id); ?>').bind('change', function() {
        location = '?app=mail&page=message&view=move&to=' + this.value + '&folder=' + $('.mailbox').attr('data-folder') + '&' + $('#mailbox-form').serialize();
    });
    
    // html dropdown list
    $('#<?php echo htmlentities($folderListWidget->id); ?> a').bind('click', function() {
        var folder = $(this).attr('data-value');
        if (!folder) return;
        location = '?app=mail&page=message&view=move&to=' + folder + '&folder=' + $('.mailbox').attr('data-folder') + '&' + $('#mailbox-form').serialize();
    });
   
    $('.mailbox .select').bind('click', function(event) {
        event.stopPropagation();
        handleMailSelections();
    });
    
    $('.mailbox .star').bind('click', function(event) {
        event.stopPropagation();
        var uid = $(this).closest('.msg').attr('data-uid');
        var view = $('.mailbox li[data-uid=' + uid + ']').hasClass('flagged') ? 'unstar' : 'star';
        location = '?app=mail&page=message&view=' + view + '&folder=' + $('.mailbox').attr('data-folder') + '&uids[]=' + uid;
    });
    
    // tools more
    $('#<?php echo htmlentities($toolsWidget->getId()); ?> a').bind('click', function(event) {
        var view = $(this).attr('data-id');
        location = '?app=mail&page=message&view=' + view + '&folder=' + $('.mailbox').attr('data-folder') + '&' + $('#mailbox-form').serialize();
    });
    
    // tools select
    $('#<?php echo htmlentities($selectWidget->getId()); ?> a').bind('click', function(event) {
        event.preventDefault();

        var id = $(this).attr('data-id');
        
        var selectors = {
            all : '.mail-checkbox',
            none : '',
            read : '.mailbox .seen .mail-checkbox',
            unread: '.mailbox .unseen .mail-checkbox',
            flagged: '.mailbox .flagged .mail-checkbox',
            unflagged: '.mailbox .unflagged .mail-checkbox'
        };
        
        var selector = selectors[id];
        
        $('.mail-checkbox').prop('checked', false);
        
        $(selector).prop('checked', true);
        
        handleMailSelections();
    });
   
    function mailCheckboxHasSelections() {
       return $('.mailbox .mail-checkbox:checked').length;
    }
    
    function handleMailSelections()
    {
        if (mailCheckboxHasSelections($('.mail-checkbox'))) {
           $('.tools-folder-list-move .btn').removeClass('disabled');
           $('.tools-more .btn').removeClass('disabled');
           $('.tools-default a').removeClass('disabled');
        } else {
           $('.tools-folder-list-move .btn').addClass('disabled');
           $('.tools-more .btn').addClass('disabled');
           $('.tools-default a').addClass('disabled');
        }
        // add class to msg when checkbox checked
        $('.mail-checkbox:checked').closest('.msg').addClass('selected');
        $('.mail-checkbox:not(:checked)').closest('.msg').removeClass('selected');
    }
    
});
    
</script>
