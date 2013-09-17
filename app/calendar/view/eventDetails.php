<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
 
$this->Doc->title = "Event Details";
?>

<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
 
$this->Doc->title = "Create an Event";
?>

<article class="data-block">
    <header>
        <h2><?php echo $Event->title; ?></h2>
    </header>
    <section>
	    <fieldset>
	    	<p><?php echo $Event->location; ?></p>
	    	<p><?php echo $Event->start; ?></p>
	    	<p><?php echo $Event->end; ?></p>
	        <p><?php echo $Event->description; ?></p>
	    </fieldset>
    </section>
</article>
