<?php

namespace app\calendar\model;

/**
 * Fiji Cloud Email
 *
 * @link       http://www.fijiwebdesign.com/
 * @copyright  Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    Fiji_App
 * @subpackage Gallery
 */
 
use Fiji\Factory;
use Fiji\App\Model;

/**
 * Event Model
 */
class Event extends Model
{
    
    public $title;
    
    public $description;
	
	public $url;
	
	public $location;
    
    public $start;
    
    public $end;
    
    public $user_id;
	
	public $allday;
	
	public $published;
	
	/**
	 * Convert start and end dates to timestamps before saving
	 */
	public function onSave()
	{
		$this->start = strtotime($this->start) ? strtotime($this->start) : $this->start;
		$this->end = strtotime($this->end) ? strtotime($this->end) : $this->end;
	}
	
	public function getStartTime()
	{
		return  $this->start ? date('h:ia', $this->start) : null;
	}
	
	public function getStartDate()
	{
		return $this->start ? date('m/d/Y', $this->start) : null;
	}
	
	public function getEndTime()
	{
		return $this->end ? date('h:ia', $this->end) : null;
	}
	
	public function getEndDate()
	{
		return $this->end ? date('m/d/Y', $this->end) : null;
	}

}
