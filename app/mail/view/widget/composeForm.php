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

use Fiji\App\Widget;

/**
 * Display the Email Composition Form
 */
class composeForm extends Widget
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

        parent::__construct('compose-form');
    }
    
    public function toHtml()
    {
        
        $attachmentWidget = new \app\mail\view\widget\attachment\attachment();
        
        ob_start();
        ?>
        
<form id="<?php echo $this->guid; ?>" class="compose-form" method="post">
    <fieldset>
        <div class="controls">
            <div class="compose-headers">
                <div class="control-group control-to">
                    <label for="to" class="control-label">To</label>
                    <input name="to" class="input-xlarge" type="text" value="<?php echo $this->to; ?>">
                    <span class="add-bcc-wrap">
                        <a href="#" class="add-cc">Cc</a>
                        <a href="#" class="add-bcc">Bcc</a>
                    </span>
                </div>
                <div class="control-group control-cc">
                    <label for="cc" class="control-label">Cc</label>
                    <input name="cc" class="input-xlarge" type="text" value="<?php echo $this->cc; ?>">
                </div>
                <div class="control-group control-bcc">
                    <label for="bcc" class="control-label">Bcc</label>
                    <input name="bcc" class="input-xlarge" type="text" value="<?php echo $this->bcc; ?>">
                </div>
                <div class="control-group control-subject">
                    <label for="subject" class="control-label">Subject</label>
                    <input name="subject" class="input-xlarge" type="text" value="<?php echo $this->subject; ?>">
                </div>
            </div>
            <div class="compose-body">
            	<div class="control-group">
	                <textarea name="body" class="wysihtml5" placeholder="Compose email&hellip;" rows="8"></textarea>
	            </div>
            </div>
        </div>
        <div class="widget-attach">
        	<?php $attachmentWidget->render(); ?>
        </div>
        <div class="form-actions">
            <button class="btn btn-alt btn-primary" id="btn-send-email" type="submit">
            	<i class="awe-plane"></i>&nbsp;Send Email</button>
            <button class="btn btn-alt btn-small" id="btn-save-email" type="submit" name="saveDraft" value="1">
            	<i class="awe-save"></i>&nbsp;Save Draft</button>
        </div>
    </fieldset>
    <input type="hidden" name="In-Reply-To" value="<?php echo htmlentities($this->inReplyTo); ?>">
    <input type="hidden" name="app" value="mail">
    <input type="hidden" name="page" value="compose">
    <input type="hidden" name="func" value="send">
    <input type="hidden" name="plupload_id" value="" />
</form>

<!-- Wysihtml5 -->
<script src="templates/chromatron/js/plugins/wysihtml5/wysihtml5-0.3.0.js"></script>
<script src="templates/chromatron/js/plugins/wysihtml5/bootstrap-wysihtml5.js"></script>

<script>
    $(document).ready(function() {

        var $composeForm = $('#<?php echo $this->guid; ?> ');
        
        $composeForm.find('.wysihtml5').wysihtml5();
        
        $composeForm.find('.add-cc').bind('click', function(event) {
            $(this).hide();
            $composeForm.find('.control-cc').show();
            event.preventDefault();
        });
        
        $composeForm.find('.add-bcc').bind('click', function(event) {
            $(this).hide();
            $composeForm.find('.control-bcc').show();
            event.preventDefault();
        });
        
        // drafts
        $composeForm.find('[name=body]').bind('keyup', function() {
        	//console.log($(this).val())
        });
        
        // form submit buttons handling
        $composeForm.find('#btn-send-email').bind('click', formValidation);
        $composeForm.find('#btn-save-email').bind('click', formValidation);
        
        // validate form and upload files
        function formValidation(event) {
            if (!$composeForm.find('[name=to]').val()) {
                return formError('Please enter a recepient.', event, $composeForm.find('[name=to]'));
            }
            if (!$composeForm.find('[name=subject]').val()) {
                return formError('Please enter a subject.', event, $composeForm.find('[name=subject]'));
            }
            if (!$composeForm.find('[name=body]').val()) {
                return formError('Please enter a message.', event, $composeForm.find('[name=body]'));
            }
            
            // upload attachments
            uploadFiles(event);
        }
        
        // display form errors
        function formError(msg, event, el) {
        	this.timer && clearTimeout(this.timer);
            $composeForm.find('#form-error-body').html(msg);
            $composeForm.find('#form-error').fadeIn('slow');
            this.timer = setTimeout(function() {
            	$composeForm.find('#form-error').fadeOut('slow');
            	$composeForm.find('.control-group').removeClass('error');
            }, 4000);
            $composeForm.find('.control-group').removeClass('error');
            $(el) && $(el).parent() && $(el).parent().addClass('error'); // @todo find parent .control-group instead of .parent()
            $(el).focus();
            event.preventDefault();
            return false;
        }
        
        // attachments
        $composeForm.find('.plupload').hide();
        $composeForm.find('.wysihtml5-toolbar').append('<li><a id="btn-attach" href="#" class="btn  btn-success"><i class="awe-paper-clip"></i>&nbsp;Attach Files</a></li>');
        $composeForm.find('.pl_start').remove();
        $composeForm.find('.pl_add').addClass('btn-success');
        $composeForm.find('#btn-attach').bind('click', function(event) {
            event.preventDefault();
            $composeForm.find('.plupload').show();
            focusOnAttachments();
            $composeForm.find('.pl_add').trigger('click');
        });
        $composeForm.find('.pl_add').prepend('<i class="awe-plus"></i>&nbsp;');
        
        function focusOnAttachments() {
            $('body, document').animate({
                scrollTop: $composeForm.find('.plupload').offset().top
            }, 500, 'linear', function() {
                
            });
        }
        
        // make sure attachments have been uploaded before submitting form
        function uploadFiles(event) {
            if ($composeForm.find('.plupload').pluploadQueue().files.length > 0) {
                // stop the form submit
                event.preventDefault();
                
                // listen for plupload completion
                $composeForm.find('.plupload').pluploadQueue().bind('StateChanged', function(up) {
                    // Called when the state of the queue is changed
                    if (up.state == plupload.STOPPED) {
                        $composeForm.find('[name=plupload_id]').val($composeForm.find('.plupload').pluploadQueue().id);
                        $composeForm.submit();
                    }
                });
                // start upload
                $composeForm.find('.plupload').pluploadQueue().start();
            }
        };
        
        // full email compose message
        // @todo only show on siteTemplate=ajax
        //formError('This is quick compose. You can go to <a href="?app=mail&page=message&view=compose">full compose here</a>.');
        
    });
    
</script>

<link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/bootstrap-wysihtml5.css'>

    <?php
    
        return ob_get_clean();
    
    }

	/**
	 * Render the widget
	 */
	public function render($format = 'html')
	{
		echo $this->toHtml();
	}
    
    
}
