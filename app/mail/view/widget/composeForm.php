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

 
namespace app\mail\view\widget;

/**
 * Display the Email Composition Form
 */
class composeForm
{
    protected $addressList; 
    
    public function __construct($to = '', $subject = '', $inReplyTo = '', $cc = '', $bcc = '')
    {
        // @note Already html encoded
        $this->to = $to;
        $this->subject = $subject;
        $this->inReplyTo = $inReplyTo;
        $this->bcc = $bcc; 
        $this->cc = $cc;
    }
    
    public function toHtml()
    {
        
        $attachmentWidget = new \app\mail\view\widget\attachment\attachment();
        
        ob_start();
        ?>
        
<form id="compose-email">
    <fieldset>
        <div class="control-group">
            <div class="compose-headers">
                <div class="controls control-to">
                    <label for="to" class="control-label">To</label>
                    <input name="to" class="input-xlarge" type="text" value="<?php echo $this->to; ?>">
                    <span class="add-bcc-wrap">
                        <a href="#" class="add-cc">Cc</a>
                        <a href="#" class="add-bcc">Bcc</a>
                    </span>
                </div>
                <div class="controls control-cc">
                    <label for="cc" class="control-label">Cc</label>
                    <input name="cc" class="input-xlarge" type="text" value="<?php echo $this->cc; ?>">
                </div>
                <div class="controls control-bcc">
                    <label for="bcc" class="control-label">Bcc</label>
                    <input name="bcc" class="input-xlarge" type="text" value="<?php echo $this->bcc; ?>">
                </div>
                <div class="controls control-subject">
                    <label for="subject" class="control-label">Subject</label>
                    <input name="subject" class="input-xlarge" type="text" value="<?php echo $this->subject; ?>">
                </div>
            </div>
            <div class="controls">
                <textarea id="reply-body" name="body" class="wysihtml5" placeholder="Compose email&hellip;" rows="8"></textarea>
            </div>
        </div>
        <div class="form-actions">
            <?php $attachmentWidget->render(); ?>
            <input type="hidden" name="plupload_id" value="" />
            <button class="btn btn-alt btn-primary" id="btn-send-email" type="submit"><i class="awe-plane"></i>&nbsp;Send Email</button>
        </div>
    </fieldset>
    <input type="hidden" name="In-Reply-To" value="<?php echo htmlentities($this->inReplyTo); ?>">
    <input type="hidden" name="app" value="mail">
    <input type="hidden" name="page" value="compose">
    <input type="hidden" name="func" value="send">
</form>

<!-- Wysihtml5 -->
<script src="templates/chromatron/js/plugins/wysihtml5/wysihtml5-0.3.0.js"></script>
<script src="templates/chromatron/js/plugins/wysihtml5/bootstrap-wysihtml5.js"></script>

<script>
    $(document).ready(function() {
        
        $('.wysihtml5').wysihtml5();
        
        $('.add-cc').bind('click', function(event) {
            $(this).hide();
            $('.control-cc').show();
            event.preventDefault();
        });
        
        $('.add-bcc').bind('click', function(event) {
            $(this).hide();
            $('.control-bcc').show();
            event.preventDefault();
        });
        
        // form handler
        $('#btn-send-email').bind('click', function(event) {
            if (!$('[name=to]').val()) {
                return formError('Please enter a recepient.', event);
            }
            if (!$('[name=subject]').val()) {
                return formError('Please enter a subject.', event);
            }
            if (!$('[name=body]').val()) {
                return formError('Please enter a message.', event);
            }
            
            // upload attachments
            uploadFiles(event);
        });
        
        // display form errors
        function formError(msg, event) {
            event.preventDefault();
            $('#form-error-body').html(msg);
            $('#form-error').modal('show');
            return false;
        }
        
        // attachments
        $('.plupload').hide();
        $('.wysihtml5-toolbar').append('<li><a id="btn-attach" href="#" class="btn  btn-success"><i class="awe-facetime-video"></i>&nbsp;Attach Files</a></li>');
        $('.pl_start').remove();
        $('.pl_add').addClass('btn-success');
        $('#btn-attach').bind('click', function(event) {
            event.preventDefault();
            $('.plupload').show();
            focusOnAttachments();
            $('.pl_add').trigger('click');
        });
        $('.pl_add').prepend('<i class="awe-plus"></i>&nbsp;');
        
        function focusOnAttachments() {
            $('body, document').animate({
                scrollTop: $('.plupload').offset().top
            }, 500, 'linear', function() {
                
            });
        }
        
        // make sure attachments have been uploaded before submitting form
        function uploadFiles(event) {
            if ($('.plupload').pluploadQueue().files.length > 0) {
                // stop the form submit
                event.preventDefault();
                
                // listen for plupload completion
                $('.plupload').pluploadQueue().bind('StateChanged', function(up) {
                    // Called when the state of the queue is changed
                    if (up.state == plupload.STOPPED) {
                        $('[name=plupload_id]').val($('.plupload').pluploadQueue().id);
                        $('#compose-email').submit();
                    }
                });
                // start upload
                $('.plupload').pluploadQueue().start();
            }
        };
        
    });
    
</script>

<div class="modal hide fade" id="form-error">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Error</h3>
  </div>
  <div class="modal-body">
    <p id="form-error-body"></p>
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" class="btn btn-primary">Ok</a>
  </div>
</div>

<style>
    
.controls label {
    width: 100px;
}

.controls input {
    width: 60%;
}

.controls label, .controls input {
    display: inline-block;
}

.compose-headers {
    margin-bottom: 10px;
}

.control-cc, .control-bcc {
    display: none;
}

.add-bcc, .add-cc {
    margin-left: 5px;
}

.add-bcc-wrap {
    /* float: right; */
}

</style>


<link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/bootstrap-wysihtml5.css'>

    <?php
    
        return ob_get_clean();
    
    }
    
    
}
