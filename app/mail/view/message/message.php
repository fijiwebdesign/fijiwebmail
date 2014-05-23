<?php
/**
 * Fiji Mail Server 
 *
 * @author    gabe@fijiwebdesign.com
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
use Fiji\Factory;

// page title
$this->Doc->title = "Email Message";

?>

<article class="data-block">
    <div class="data-container">
        
        <div class="data-container mail-message">
            <header>
                <h2><?php echo $subject; ?></h2>
                <div class="headers">
                    <div class="header from"><?php echo $fromWidget->toHtml(); ?></div>
                    <div class="header to"><span>to</span><?php echo $toWidget->toHtml(); ?></div>
                    <div class="header date"><span>on</span><?php echo date('D jS M Y', strtotime($message->date)); ?></div>
                    <div class="header time"><span>at</span><?php echo date('h:ia', strtotime($message->date)); ?></div>
                    <?php
                    if (count($message->labels) > 0) {
                    	echo '<div class="header labels">';
	                    foreach($message->labels as $label) {
	                        echo '<span class="label label-' . $label->name . '" style="background:' . $label->color . ';background:#' . $label->color . '">'
	                         . $label->title 
	                         . ' <a href="?app=mail&page=message&folder=' . htmlentities($this->folder) . '&view=removeLabel&label=' . htmlentities($label) . '&uids[]=' . $uid . '" class="awe-remove"></a></span>';
	                    }
						echo '</div>';
					}
					?>
					<!--
                    <a href="#" class="email-reply-btn btn"><span class="awe-share-alt"></span>&nbsp;Reply</a>
                   -->
                   <div class="mail-pagination pagination"><?php echo $PaginationWidget->toHtml(); ?></div>
                   
                </div>
            </header>
            <section class="email-body">
                <div>
                    <iframe id="email-body-iframe" src="?app=mail&page=message&view=body&folder=<?php echo htmlentities(urlencode($this->folder)); ?>&uid=<?php echo $uid; ?>&siteTemplate=ajax" 
                        seamless style="width:100%;overflow:hidden;border:0;" scrolling="no"></iframe>
                </div>
                <div id="email-attachments">
                    <?php 
                    if (count($attachmentModels)) {
                        echo '<h4>' . count($attachmentModels) . ' Attachments</h4>';
                        echo '<ul>';
                        foreach($attachmentModels as $attachmentModel) {
                            $url = $attachmentModel->getUrl($uid);
                            $thumb = '';
                            if ($attachmentModel->isImage()) {
                                // @todo resize image
                                $thumb = '<img src="' . $url . '&disposition=inline" style="max-width:150px;max-height:150px" />';
                            }
                            
                            echo '<li>' . $thumb . '<a href="' . $url . '" target="_blank">' . $attachmentModel->title . '</a></li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>
            </section>
            <section class="reply-fake">
                <fieldset>
                    <div class="reply-textarea">
                        <p>Click here to compose a <a href="#">Reply</a></p>
                    </div>
                </fieldset>
            </section>
            <section class="reply-wysiwyg">
                <?php
                    $composeForm = new app\mail\view\widget\composeForm($from, $subject, $inReplyTo);
                    echo $composeForm->toHtml();
                ?>
            </section>
        </div>
    </div>
</article>


<script>
    
$(function() {
    function focusOnReply() {
        $('body, document').animate({
            scrollTop: $('.reply-wysiwyg').offset().top
        }, 500, 'linear', function() {
            $('.wysihtml5-sandbox')[0].contentWindow.focus();
        });
    }
    function showWysiwyg(event) {
        event.stopPropagation();
        event.preventDefault();
        
        $('.reply-fake').hide();
        $('.reply-wysiwyg').show('slow');
        focusOnReply();
        setTimeout(function() { 
        	//$('#btn-attach').removeClass('btn-success'); 
        }, 2000);
    }
    $('.email-reply-btn').bind('click', showWysiwyg);
    
    $('.reply-textarea').bind('click', showWysiwyg);
    
    // resize message iframe width and height to fix message
    var autoResizeIframeTimer = setInterval(function() {
        autoResizeIframe('email-body-iframe');
    }, 100);
    // stop resizing when message is loaded
    document.getElementById('email-body-iframe').onload = function() {
        clearInterval(autoResizeIframeTimer);
        autoResizeIframe('email-body-iframe');
    }
    
});

function autoResizeIframe(id, stop) {
    
    var height =document.getElementById(id).contentWindow.document .body.scrollHeight;
    var width =document.getElementById(id).contentWindow.document .body.scrollWidth;

    document.getElementById(id).height= (height) + "px";
    document.getElementById(id).width= (width) + "px";

}
    
</script>

<style type="text/css">

.mail-message .pagination .pagination-list > li:first-child a {
	border-radius: 4px 0 0 4px;
}

.mail-message .pagination .pagination-list > li:last-child a {
	border-radius: 0 4px 4px 0;
}

.pagination-data .to {
	display: none;
}
.pagination-data .end {
	display: none;
}
</style>