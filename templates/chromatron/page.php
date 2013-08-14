<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js ie8 ie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 ie" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title><?php echo htmlentities($Doc->title); ?></title>
        <meta name="description" content="">
        <meta name="robots" content="index, follow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <base href="<?php echo htmlentities($Uri->getBase()); ?>">
        
        <!-- jQuery Snippet Styles -->
        <link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/jquery.snippet.css'>
        
        <!-- Styles -->
        <link rel='stylesheet' type='text/css' href='templates/chromatron/css/chromatron-blue.css'>
        <link rel='stylesheet' type='text/css' href='templates/chromatron/css/mail.css'>
        
        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="img/icons/favicon.html">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/icons/apple-touch-icon-114-precomposed.html">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/icons/apple-touch-icon-72-precomposed.html">
        <link rel="apple-touch-icon-precomposed" href="img/icons/apple-touch-icon-57-precomposed.html">
        
        <!-- mail specific -->
        <link rel='stylesheet' type='text/css' href='public/css/mail.css'>
        
        
        <!-- JS Libs -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/libs/jquery.js"><\/script>')</script>
        <script src="templates/chromatron/js/libs/modernizr.js"></script>
        <script src="templates/chromatron/js/libs/selectivizr.js"></script>
        
        <script>
            $(document).ready(function(){
                
                // Tooltips for nav badge
                $('.main-navigation .badge').tooltip({
                    placement: 'bottom'
                });
                
                // Tooltips for widgets
                $('.widget [title]').tooltip({
                    placement: 'left'
                });
                
                // Tooltips for content
                $('.content-block [title]').tooltip({
                    placement: 'top'
                });
                
                // Close button for widgets
                $('.widget').alert();
                
                // Remove tooltip when widget is closed
                $('.widget').bind('close', function () {
                    $(this).find('.close').tooltip('destroy');
                })
                
                // Tabs
                $('.demoTabs a').click(function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                })
                
                // Tickets
                $('#ticketsDemo .ticket-data-activity a').click(function (e) {
                    e.preventDefault();
                })
                
                
            });
        </script>
        
        <script type="text/javascript">
            //var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-22557155-2"]);_gaq.push(["_trackPageview"]);(function(){var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();
        </script>
        
        <style type="text/css">
            
            
            
            
        </style>
        
        
    </head>
    <body>
        
        <!-- Main page container -->
        <div class="container-fluid">
        
            <!-- Left (navigation) side -->
            <section class="navigation-block">
                
                <!-- User profile -->
                <?php echo $Doc->userProfile; ?>
                <!-- /User profile -->
                
                <!-- Responsive navigation -->
                <a href="#" class="btn btn-navbar btn-large" data-toggle="collapse" data-target=".nav-collapse"><span class="fam-heart"></span>Apps</a>
                
                <!-- Main navigation -->
                <?php $Doc->renderWidgets('navigation'); ?>
                <!-- /Main navigation -->
                
                
            </section>
            <!-- /Left (navigation) side -->
            
            <!-- Right (content) side -->
            <section class="content-block" role="main">
            
                <?php echo $Doc->breadcrumbs; ?>
                
                <!-- Grid row -->
                <div class="row-fluid">
                
                    <?php echo $Doc->content; ?>
                
                </div>
                <!-- /Grid row -->
            
            </section>
            <!-- /Right (content) side -->
            
        </div>
        <!-- /Main page container -->
        
        <!-- Scripts -->
        <script src="templates/chromatron/js/navigation.js"></script>
        
        <!-- Bootstrap scripts -->
        <!--
        <script src="js/bootstrap/bootstrap-tooltip.js"></script>
        <script src="js/bootstrap/bootstrap-dropdown.js"></script>
        <script src="js/bootstrap/bootstrap-tab.js"></script>
        <script src="js/bootstrap/bootstrap-button.js"></script>
        <script src="js/bootstrap/bootstrap-alert.js"></script>
        <script src="js/bootstrap/bootstrap-popover.js"></script>
        <script src="js/bootstrap/bootstrap-collapse.js"></script>
        <script src="js/bootstrap/bootstrap-modal.js"></script>
        <script src="js/bootstrap/bootstrap-transition.js"></script>
        -->
        <script src="templates/chromatron/js/bootstrap/bootstrap.min.js"></script>
        
        <!-- Block TODO list -->
        <script>
            $(document).ready(function() {
                
                $('.todo-block input[type="checkbox"]').click(function(){
                    $(this).closest('tr').toggleClass('done');
                });
                $('.todo-block input[type="checkbox"]:checked').closest('tr').addClass('done');
                
            });
        </script>
        
        <!-- jQuery Snippet -->
        <script src="templates/chromatron/js/plugins/snippet/jquery.snippet.min.js"></script>
        
        <script>
            $(document).ready(function() {
            
                $('pre.html').snippet('html',{style:"rand01"});
                
            
            });
        </script>
        
        <?php echo $Doc->notifications; ?>

        <script type="text/javascript" src="templates/chromatron/js/plugins/smartWizard/jquery.smartWizard-2.0.js"></script>
        
    </body>

</html>
