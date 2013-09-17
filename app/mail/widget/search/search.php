<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace app\mail\widget\search;
 
use Fiji\Factory;

class search extends \Fiji\App\Widget
{
    protected $User;
	protected $Req;
    
    public function __construct($model)
    {
    	
		// we need a session
		$this->User = Factory::getUser();
		
		// http request
		$this->Req = Factory::getRequest();
		
        parent::__construct($model);
    }
    
    public function render($format = 'html')
    {
    	
		if (!$this->User->isAuthenticated()) {
		    return false;
		}
		
        ?>
<ul class="data-header-actions">
    <li>
        <form class="form-search">
            <div class="control-group">
                <div class="controls">
                    <input name="q" class="search-query" type="text" value="<?php echo htmlentities($this->Req->getVar('q')); ?>">
                    <button class="btn" type="submit">Search</button>
                    
                    <input type="hidden" name="app" value="mail">
                </div>
            </div>
        </form>
    </li>
</ul>
<?php
    }
}

?>