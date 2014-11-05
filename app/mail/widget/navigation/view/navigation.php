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

$app = $Req->get('app', 'mail');
$folder = $Req->get('folder', 'inbox');

if ($app == 'mail') :
	$folderListWidget = new app\mail\view\widget\folderList('folder-list');
?>
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

<script>

jQuery(function() {
    // compose email modal
    showComposeEmailModal = function(url) {
        //event.preventDefault();
        $('#modal-compose-email').modal({"backdrop": "static"});
        
        // load compose form from remote if not exist
        loadComposeEmailModalBody(url);
    }
    
    // load a url into compose email body
    function loadComposeEmailModalBody(url) {
        if (!$('#modal-compose-email-body').html()) {

            var modal = $('#modal-compose-email').detach();
            $(document.body).append(modal);

            $.ajax(url + '&siteTemplate=ajax', {
                complete: function(xhr) {
                    var html = xhr.responseText;
                    $('#modal-compose-email-body').html(html)
                }
            });
        }
    }
    
    // url of email compose page
    var composeEmailUrl = '?app=mail&page=message&view=compose';
    
    $('#side-note-compose-email .btn-compose').click(function(event) {
        event.preventDefault();
        showComposeEmailModal(composeEmailUrl);
    });
    
    $('#modal-compose-email .btn-send-email').click(function(event) {
        event.preventDefault();
        $('#btn-send-email').click();
    });
    
    $('#modal-compose-email .btn-save-email').click(function(event) {
        event.preventDefault();
        $('#btn-save-email').click();
    });
    
    // preload compose email form 
    setTimeout(function() {
        loadComposeEmailModalBody(composeEmailUrl); // @todo fix bug, reloads jquery
    }, 500);
});

</script>

<div class="modal hide fade" id="modal-compose-email">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <header class="btn-group open" id="compose-select">
        <h3 id="event-details-title" data-toggle="dropdown">Compose Email</h3>
        <a href="#" data-toggle="dropdown" class="caret"></a>
        <ul class="dropdown-menu">
            <li><a href="?app=mail&page=message&view=compose">Compose Email Full View</a></li>
        </ul>
    </header>
  </div>
  <div class="modal-body">
    <p id="modal-compose-email-body"></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-alt btn-primary btn-send-email" type="submit"><i class="awe-plane"></i>&nbsp;Send Email</button>
    <button class="btn btn-alt btn-small btn-save-email" type="submit" name="saveDraft" value="1"><i class="awe-save"></i>&nbsp;Save Draft</button>
  </div>
</div>

<style type="text/css">

#modal-compose-email {
    width: 95%;
    min-width: 800px;
    max-width: 1000px;  
    position: absolute;
    right: 5px;
}
.modal-bottom-right {
    margin-top: 0;
    margin-bottom: -5px;
    top: auto;
    bottom: 0px;
    right: 5px;
    left: auto;
}
#modal-compose-email .wysihtml5-sandbox {
    height: auto !important;
}
#modal-compose-email .form-actions .btn {
    display: none;
}
#modal-compose-email fieldset {
    padding: 15px;
    width: auto;
}
#modal-compose-email .modal-body {
    overflow-x: hidden;
}
#modal-compose-email .modal-body header {
    display: none;
}
</style>
