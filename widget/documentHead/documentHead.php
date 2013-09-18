<?php
/**
 * Fiji Mail Server 
 *
 * @author    gabe@fijiwebdesign.com
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace widget\documentHead;

use Fiji\App\Widget;

/**
 * Generate HTML to add to header of page
 */
class documentHead extends Widget
{
	/**
	 * @var $lines Array lines of output
	 */
	private $lines = array();

	/**
	 * Render the widget
	 */
    public function render($format = 'html')
    {
        echo implode("\n", $this->lines);
    }
	
	/**
	 * Add a JavaScript file
	 * @param $url URL of JS file
	 */
	public function addJavascript($url)
	{
		$this->addLine('<script type="text/javascript" src="' . htmlentities($url, ENT_QUOTES, 'UTF-8') . '"> </script>');
	}
	
	/**
	 * Add a CSS file
	 * @param $url Url of CSS file
	 */
	public function addCss($url)
	{
		$this->addLine('<link type="text/css" rel="stylesheet" href="' . htmlentities($url, ENT_QUOTES, 'UTF-8') . '" />');
	}
	
	/**
	 * Add a line
	 * @param $line Content
	 */
	public function addLine($line) {
		$this->lines[] = $line;
	}
}
