<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace widget\userProfile;
 
use Fiji\Factory;

class userProfile extends \Fiji\App\Widget
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function render($format = 'html')
    {
    	// only authenticated users can view their profile
		if (!$this->User->isAuthenticated()) {
		    return false;
		}
		
        ?>
<section class="user-profile">
    <figure>
        <img alt="Avatar" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($this->User->username)); ?>">
        <figcaption>
            <strong><a href="#" class=""><?php echo $this->User->username; ?></a></strong>
            <em><?php echo isset($this->User->group) ? $this->User->group : 'Member'; ?></em>
            <ul>
                <li><a class="btn btn-primary btn-flat" href="#" title="Edit your settings">settings</a></li>
                <li><a class="btn btn-primary btn-flat" href="?app=auth&view=logout" title="Securely logout from application">logout</a></li>
            </ul>
        </figcaption>
    </figure>
</section>
<?php
    }
}
?>