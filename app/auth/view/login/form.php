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
 
// get the document
$Doc = Factory::getSingleton('Fiji\App\Document');
$App = Factory::getSingleton('Fiji\App\Application');
$User = Factory::getSingleton('Fiji\App\User');

?>

<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js ie8 ie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 ie" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    
<head>
        <meta charset="utf-8">
        <title>Login | Fiji Communication Server</title>
        <meta name="description" content="">
        <meta name="robots" content="index, follow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Styles -->
        <link rel='stylesheet' type='text/css' href='templates/chromatron/css/chromatron-blue.css'>
        
        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="img/icons/favicon.html">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/icons/apple-touch-icon-114-precomposed.html">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/icons/apple-touch-icon-72-precomposed.html">
        <link rel="apple-touch-icon-precomposed" href="img/icons/apple-touch-icon-57-precomposed.html">
        
        <!-- JS Libs -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="templates/chromatron/js/libs/jquery.js"><\/script>')</script>
        <script src="templates/chromatron/js/libs/modernizr.js"></script>
        <script src="templates/chromatron/js/libs/selectivizr.js"></script>
        
        <script>
            $(document).ready(function(){
                
                // Close button for widgets
                $('.widget').alert();
                
            });
        </script>
        <script src="templates/chromatron/js/bootstrap/bootstrap.min.js"></script>
        
        <style>
        
        .login-page {
            height: auto;
        }
        
        .login-page .login-container {
            margin-top: 20px;
            width: 100%;
            max-width: 100%;
        }
        
        .login-page .login-container > section {
            text-align: center;
            margin: 0;
            border-left: 0;
            border-right: 0;
            border-radius: 0;
            background: #fff url(public/images/bgs/197928018_c0a71626ed_o2.jpg);
        }
            
        .login-page .login-container h1 {
            text-align: center;
        }
        
        #login-form {
            width: 375px;
            margin: auto;
            text-align: left;
        }
        
        #login-form > fieldset {
            background: #fff;
        }
        
        .login-page .login-container h1 .brand {
            background: no-repeat bottom center url(public/images/Fiji-Mail-Cloud-Logo.png);
            height: 100px;
            width: 235px;
            display: block;
            margin: auto;
        }
        
        #login-nav {
            margin-top: -20px;
        }
        
        #copyright {
            text-align: right;
            color: #8d8d8d;
            text-shadow: 0 1px 1px #ffffff;
            margin-top: 10px;
            padding-right: 10px;
        }
        
        body.login-page {
            background-color: #f0f0f0;
        }
            
        </style>
    </head>
    <body class="login-page">
        
        <!-- Main login container -->
        <div class="login-container">
            
            <!-- Login page logo -->
            <h1><a class="brand" href="#">Fiji Communication Server</a></h1>
            
            <section>
                
                <!-- Sample alert -->
                <div class="alert-wrap"></div>
                <?php if ($status == 'fail') : ?>
                <div class="alert alert-info alert-block fade in">
                    <button class="close" data-dismiss="alert">&times;</button>
                    <strong>Login Failed!</strong> Please check your username and password and try again.
                </div>
                <?php endif; ?>
                
                <!-- /Sample alert -->
                
                <!-- Login form -->
                <form id="login-form" method="post" action="">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="login">Username</label>
                            <div class="controls">
                                <input id="icon" type="text" placeholder="Your username" name="username">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="password">Password</label>
                            <div class="controls">
                                <input id="password" type="password" placeholder="Password" name="password">
                                <label class="checkbox">
                                    <input id="optionsCheckbox" type="checkbox" value="option1"> Remember me
                                </label>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="btn btn-primary btn-alt" type="submit"><span class="awe-signin"></span> Log in</button>
                        </div>
                        
                        <!-- Login page navigation -->
                        <nav id="login-nav">
                            <ul>
                                <li><a href="#">Lost password?</a></li>
                                <li><a href="#">Support</a></li>
                            </ul>
                        </nav>
                        <!-- Login page navigation -->
            
                    </fieldset>
                    
                    <input type="hidden" name="app" value="auth" />
                    <input type="hidden" name="view" value="login" />
                </form>
                <!-- /Login form -->
                
            </section>
            
            <!-- copyright -->
            <footer id="copyright">Fiji Cloud Email &copy; <a href="http://www.fijisoftware.com/">FijiSoftware.com</a></footer>
            <!-- /copyright -->
            
        </div>
        <!-- /Main login container -->
        
        <!-- Bootstrap scripts -->
        <!--
        <script src="templates/chromatron/js/bootstrap/bootstrap.min.js"></script>
        -->
     
<?php 
require $App->getPathBase() . '/templates/chromatron/widgets/notifications.php';
?>
        
    </body>

</html>
