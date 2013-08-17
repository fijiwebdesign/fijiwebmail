
<link rel="stylesheet" href="app/mail/view/widget/attachment/css/jquery.plupload.queue.css">
<link rel="stylesheet" href="app/mail/view/widget/attachment/css/jquery.ui.plupload.css">
<script src="app/mail/view/widget/attachment/js/plupload.full.js"></script>
<script src="app/mail/view/widget/attachment/js/jquery.plupload.queue.js"></script>
<script>
    $(document).ready(function() {
        
        $('.plupload').pluploadQueue({
            runtimes : 'html5,gears,flash,silverlight,browserplus',
            url : 'app/mail/view/widget/attachment/upload.php',
            max_file_size : '10mb',
            chunk_size : '1mb',
            unique_names : true,
            resize : {},
            filters : [
            /* @todo Define in configuration
                {title : "Image files", extensions : "jpg,gif,png,jpeg"},
                {title : "Zip files", extensions : "zip"}
                */
            ],
            flash_swf_url : 'js/plugins/plUpload/plupload.flash.swf',
            silverlight_xap_url : 'js/plugins/plUpload/plupload.silverlight.xap',
            
        });
        $(".plupload_header").remove();
        $(".plupload_progress_container").addClass("progress").addClass('progress-striped');
        $(".plupload_progress_bar").addClass("bar");
        $(".plupload_button").each(function(e){
            if($(this).hasClass("plupload_add")){
                $(this).attr("class", 'btn btn-primary btn-alt pl_add btn-small');
            } else {
                $(this).attr("class", 'btn btn-success btn-alt pl_start btn-small');
            }
        });
        
    });
</script>

<div class="plupload"></div>