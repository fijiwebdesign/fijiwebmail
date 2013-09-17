<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace widget\notifications;
 
use Fiji\Factory;

class notifications extends \Fiji\App\Widget
{
    
    public function __construct($model = null)
    {
        parent::__construct($model);
    }
    
    public function render($format = 'html', $theme = 'success')
    {

		if ($notifications = $this->User->getNotifications()) : ?>
		
		<link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/jquery.jgrowl.css'>
		<script type="text/javascript" src="templates/chromatron/js/plugins/jGrowl/jquery.jgrowl.js"></script>
		
		<?php foreach($notifications as $notification) : ?>
		<script>
		$.jGrowl("<?php echo htmlentities($notification, ENT_QUOTES, 'UTF-8'); ?>", {
		    life: 5000,
		    theme: '<?php echo htmlentities($theme); ?>'
		});
		</script>
		<?php endforeach; ?>
		
		<?php $this->User->clearNotifications(); ?>
		
		<?php endif; ?>
		<?php
    }
}
