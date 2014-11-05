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
        <script src="templates/chromatron/js/libs/jquery.js"></script>
        <script src="templates/chromatron/js/libs/modernizr.js"></script>
        <script src="templates/chromatron/js/libs/selectivizr.js"></script>
 
    </head>
    <body>
        <?php echo $Doc->content; ?>
    </body>

</html>
