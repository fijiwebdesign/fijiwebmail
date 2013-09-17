<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
$eventSubmitText = 'Create Event';
$this->Doc->title = "Create an Event";
$header = 'Create An Event';
if ($Event->getId()) {
	$eventSubmitText = 'Update Event';
	$this->Doc->title = "Update Event";
	$header = 'Update Event';
}
?>

<article class="data-block">
    <header>
        <h2><?php echo $header; ?></h2>
    </header>
    <section>
        <form id="compose-event" method="post">
		    <fieldset>
		        <div class="control-group">
		            <div class="event-compose-headers">
		                <div class="controls control-to">
		                    <label for="title" class="control-label">Event Title</label>
		                    <input name="title" class="input-xlarge" type="text" value="<?php echo htmlentities($Event->title); ?>">
		                </div>
		                <div class="controls control-start">
		                    <label for="start" class="control-label">Start</label>
		                	<span class="input-append">
								<input name="startDate" class="datepicker input-small" value="<?php echo htmlentities($Event->startDate); ?>" type="text">
								<span class="add-on">
									<i class="awe-calendar"></i>
								</span>
							</span>
							<label for="stateTime" class="timepicker-label">at</label>
							<input name="startTime" class="timepicker input-small" value="<?php echo htmlentities($Event->startTime); ?>" type="text">
							<span class="timepicker-example small" data-for="startTime">eg: 8:15am</span>
		                </div>
		                <div class="controls control-end">
		                    <label for="end" class="control-label">End</label>
		                    <span class="input-append">
								<input name="endDate" class="datepicker input-small" value="<?php echo htmlentities($Event->endDate); ?>" type="text">
								<span class="add-on">
									<i class="awe-calendar"></i>
								</span>
							</span>
							<label for="endTime" class="timepicker-label">at</label>
							<input name="endTime" class="timepicker input-small" value="<?php echo htmlentities($Event->endTime); ?>" type="text">
							<span class="timepicker-example" data-for="endTime">eg: 8:30pm</span>
		                </div>
		            </div>
		            <div class="controls control-location">
		                    <label for="location" class="control-label">Location</label>
		                    <input name="location" class="input-xlarge" type="text" value="<?php echo htmlentities($Event->location); ?>">
		                </div>
		            <div class="controls">
		                <textarea id="description" name="description" class="wysihtml5" placeholder="Event Details&hellip;" rows="8"><?php echo htmlentities($Event->details); ?></textarea>
		            </div>
		        </div>
		        <div class="form-actions">
		            <button class="btn btn-alt btn-primary" id="btn-send-event" type="submit"><i class="awe-plane"></i>&nbsp;<?php echo $eventSubmitText; ?></button>
		        </div>
		    </fieldset>
		    <input type="hidden" name="start" value="<?php echo htmlentities($Event->start); ?>">
		    <input type="hidden" name="end" value="<?php echo htmlentities($Event->end); ?>">
		    <input type="hidden" name="id" value="<?php echo intval($Event->id); ?>">
		    <input type="hidden" name="timezoneOffset">
		    <input type="hidden" name="app" value="calendar">
		    <input type="hidden" name="view" value="saveEvent">
		</form>
    </section>
</article>

<!-- Wysihtml5 -->
<script src="templates/chromatron/js/plugins/wysihtml5/wysihtml5-0.3.0.js"></script>
<script src="templates/chromatron/js/plugins/wysihtml5/bootstrap-wysihtml5.js"></script>

<!-- Date Picker -->
<script src="templates/chromatron/js/plugins/datepicker/bootstrap-datepicker.js"></script>

<script>
    $(document).ready(function() {
        
        $('.wysihtml5').wysihtml5();
        
        // form validation
        function validateForm(event) {
            if (!$('[name=title]').val()) {
                return formError('Please enter a title.', event);
            }
            if (!$('[name=start]').val()) {
                return formError('Please enter a start date.', event);
            }
            if (!$('[name=end]').val()) {
                return formError('Please enter an end date.', event);
            }
        }
        
        // display form errors
        function formError(msg, event) {
            event.preventDefault();
            $('#form-error-body').html(msg);
            $('#form-error').modal('show');
            return false;
        }
        
        // initialize date pickers
        $('.datepicker').datepicker({
			"autoclose": true
		});
		
		// set start and end times before submitting form
		$('#btn-send-event').bind('click', function(event) {
			
			var tzOffset = new Date().getTimezoneOffset() / 60;
			if (tzOffset > 0) {
				tzOffset = ' -' + tzOffset + '00';
			} else {
				tzOffset = ' +' + tzOffset + '00';
			}
			
			$('[name=start]').val($('[name=startDate]').val() + ' ' + $('[name=startTime]').val() + tzOffset);
			$('[name=end]').val($('[name=endDate]').val() + ' ' + $('[name=endTime]').val() + tzOffset);
			
			validateForm();
			
		});
		
		$('.timepicker').bind('focus', function() {
			$('[data-for=' + $(this).attr('name') + ']').show();
		});
		
		$('.timepicker').bind('blur', function() {
			$('[data-for=' + $(this).attr('name') + ']').hide();
		});
        
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

<link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/bootstrap-wysihtml5.css'>
