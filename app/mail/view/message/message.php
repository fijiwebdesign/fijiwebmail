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
 
?>

<style type="text/css">
    
.subject {
    width: 70%;
}

.from {
    font-weight: bold;
    text-overflow: ellipsis;
    display: block;
    overflow: hidden;
}

.header {
    float: left;
    font-weight: bold;
}

.header span {
    font-weight: normal;
    padding: 0 5px;
}

.email-reply-btn {
    float: right;
    margin-top: -10px;
}

.email-reply-btn .icon-share-alt {
    transform:rotate(180deg);
    -ms-transform:rotate(180deg); /* IE 9 */
    -moz-transform:rotate(180deg); /* Firefox */
    -webkit-transform:rotate(180deg); /* Safari and Chrome */
    -o-transform:rotate(180deg); /* Opera */
}

.wysihtml5 {
    width: 100%;
}

.email-body {
    padding-bottom: 10px;
    border-bottom: 3px solid #e6e6e6;
}

.reply-wysiwyg {
    margin-top: 10px;
    display: none;
}

.addressList {
    margin: 0 !important;
    padding: 0;
    list-style: none;
    display: inline-block;
}

.addressList li {
    display: inline-block;
}

.reply-fake {
    margin-top: 10px;
}

.reply-textarea {
    border: 1px solid rgb(204, 204, 204);
    -webkit-box-shadow: rgb(217, 217, 217) 0px 0px 4px 0px inset;
    box-shadow: rgb(217, 217, 217) 0px 0px 4px 0px inset;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
    width: 100%;
    height: 100px;
}

.reply-textarea p {
    padding: 10px;
}

#email-attachments {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

#email-attachments ul {
    margin-left: 0;
}

#email-attachments li {
    list-style: none;
    padding-bottom: 10px;
}

#email-attachments li img {
    margin-right: 10px;
}
   
</style>

<article class="data-block">
    <div class="data-container">
        
        <div class="data-container">
            <header>
                <h2><?php echo $subject; ?></h2>
                <div class="headers">
                    <div class="header from"><?php echo $fromWidget->toHtml(); ?></div>
                    <div class="header to"><span>to</span><?php echo $toWidget->toHtml(); ?></div>
                    <div class="header date"><span>on</span><?php echo date('D jS M Y', strtotime($message->date)); ?></div>
                    <div class="header time"><span>at</span><?php echo date('h:ia', strtotime($message->date)); ?></div>
                    <a href="#" class="email-reply-btn btn"><span class="icon-share-alt"></span>Reply</a> 
                </div>
            </header>
            <section class="email-body">
                <div>
                    <?php echo $body; ?>
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
    }
    $('.email-reply-btn').bind('click', showWysiwyg);
    
    $('.reply-textarea').bind('click', showWysiwyg);
});
    
</script>

