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

$Doc = Factory::getDocument();
$Req = Factory::getRequest();

// allways load without the template widgets and <head> etc.
if ($Req->get('siteTemplate') !== 'ajax') {
    $Req->set('siteTemplate', 'app');
}

?>

    <script>
    jQuery(function() {
        $('body').addClass('login-page');
    });
    </script>
        
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
            background-size: cover;
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
            background: no-repeat bottom center url(public/images/logo_blue_80px.png) !important;
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
            
            <div class="login-container">

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

            </div>

            <?php echo $Doc->renderWidgets('notifications', 'html'); ?>
