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

// we need a session
$User = Factory::getSingleton('Fiji\App\User');

if (!$User->isAuthenticated()) {
    return;
}
?>

<section class="user-profile">
    <figure>
        <img alt="Avatar" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($User->username)); ?>">
        <figcaption>
            <strong><a href="#" class=""><?php echo $User->username; ?></a></strong>
            <em><?php echo isset($User->group) ? $User->group : 'Member'; ?></em>
            <ul>
                <li><a class="btn btn-primary btn-flat" href="#" title="Edit your settings">settings</a></li>
                <li><a class="btn btn-primary btn-flat" href="?app=auth&func=logout" title="Securely logout from application">logout</a></li>
            </ul>
        </figcaption>
    </figure>
</section>