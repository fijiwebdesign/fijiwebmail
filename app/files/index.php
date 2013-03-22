<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
?>

<!-- Grid row -->
                <div class="row-fluid">
                
                    <!-- Data block -->
                    <article class="span12 data-block">
                        <div class="data-container">
                            <header>
                                <h2>Upload Files</h2>
                            </header>
                            <section>
                                <p>Allows you to upload files using HTML5 Gears, Silverlight, Flash, BrowserPlus or normal forms, providing some unique features such as upload progress, image resizing and chunked uploads.</p>
                                <div class="plupload"></div>
                            </section>
                        </div>
                    </article>
                    <!-- /Data block -->
                    
                </div>
                <!-- /Grid row -->
                

<script src="templates/chromatron/js/bootstrap/bootstrap.min.js"></script>
        
        <!-- jQuery plUpload -->
        <script src="templates/chromatron/js/plugins/plUpload/plupload.full.js"></script>
        <script src="templates/chromatron/js/plugins/plUpload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
        <script>
            $(document).ready(function() {
                
                $('.plupload').pluploadQueue({
                    runtimes : 'html5,gears,flash,silverlight,browserplus',
                    url : 'upload/upload.php',
                    max_file_size : '10mb',
                    chunk_size : '1mb',
                    unique_names : true,
                    resize : {width : 320, height : 240, quality : 90},
                    filters : [
                        {title : "Image files", extensions : "jpg,gif,png"},
                        {title : "Zip files", extensions : "zip"}
                    ],
                    flash_swf_url : 'js/plugins/plUpload/plupload.flash.swf',
                    silverlight_xap_url : 'js/plugins/plUpload/plupload.silverlight.xap'
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
        
<!-- jQuery plUpload -->
        <link rel="stylesheet" href="templates/chromatron/css/plugins/jquery.plupload.queue.css">
        <link rel="stylesheet" href="templates/chromatron/css/plugins/jquery.ui.plupload.css">