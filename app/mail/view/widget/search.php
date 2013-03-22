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

// http request
$Req = Factory::getSingleton('Fiji\App\Request');

if (!$User->isAuthenticated()) {
    return;
}

?>

<ul class="data-header-actions">
    <li>
        <form class="form-search">
            <div class="control-group">
                <div class="controls">
                    <input name="q" class="search-query" type="text" value="<?php echo htmlentities($Req->getVar('q')); ?>">
                    <button class="btn" type="submit">Search</button>
                    
                    <input type="hidden" name="app" value="mail">
                </div>
            </div>
        </form>
    </li>
</ul>