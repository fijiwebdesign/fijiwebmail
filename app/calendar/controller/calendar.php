<?php

namespace app\calendar\controller;

/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

use Fiji\Factory;
use Fiji\App\View;
use Fiji\App\Controller;
use Exception;

/**
 * Events Calendar Controller
 */
class calendar extends Controller
{
    
    protected $User;
    protected $App;
    protected $Req;
    protected $Doc;
    
    public function __construct(View $View = null)
    {
        $this->User = Factory::getUser();
        $this->App = Factory::getApplication();
        $this->Req = Factory::getRequest();
        $this->Doc = Factory::getDocument();
        
        //make sure user is logged in
        if (!$this->User->isAuthenticated()) {
            $this->App->setReturnUrl($this->Req->getUri());
            $this->App->redirect('?app=auth', 'Please login to view your calendar.');
        }
        
        // call the controllers correct method
        parent::__construct($View);
    }

    /**
     * Display the calendar
     */
    public function index()
    {
        // template
        require( __DIR__ . '/../view/calendar.php');
    }
	
	/**
	 * Display the events
	 */
	public function events()
	{
		// event start and end times
		$startTime = $this->Req->get('start');
		$endTime = $this->Req->get('end');
		
		$Event = Factory::createModel('Event');
		$EventList = Factory::createModelCollection($Event);
		
		$EventList->find(array('user_id' => $this->User->getId()));
		
		$events = $EventList->toArray();
		
		// set start and end dates to Javascript readable values
		foreach($events as $i => $event) {
			foreach($event as $name => $value) {
				if ($name == 'start' || $name == 'end') {
					//$events[$i][$name] = @date(DATE_RSS, $value);
					//$events[$i][$name] = intval($events[$i][$name]);
				}
			}
			//$events[$i]['url'] = '?app=calendar#' . $event['id'];
			//$events[$i]['url'] = "javascript:showEventDetails('?app=calendar&view=editEvent&id=" . $event['id'] . "');";
		}
		
		// show events as json response
		header('Content-Type: text/javascript');
		echo json_encode($events);
		
		die;
	}
	
	/**
	 * Display the event details
	 */
	public function event()
	{
		// event id
		$id = $this->Req->get('id');
		
		$format = $this->Req->get('format');
		
		$Event = Factory::createModel('Event');
		$Event->findById($id);
		
		if ($Event->user_id != $this->User->getId()) {
			throw new Exception('Invalid User');
		}
		
		$event = $Event->toArray();
		
		// show events as json response
		if ($format == 'json') {
			header('Content-Type: text/javascript');
			echo json_encode($event);
			die;
		} else {
			require( __DIR__ . '/../view/eventDetails.php');
		}
		
	}
	
	/**
	 * Add an event
	 */
	public function editEvent()
	{
		// event Id
		$id = $this->Req->get('id');
		$siteTemplate = $this->Req->get('siteTemplate');
		
		$Event = Factory::createModel('Event');
		if ($id) {
			$Event->findById($id);
		}
		
		// template
		if ($siteTemplate == 'ajax') {
			require( __DIR__ . '/../view/editEvent.ajax.php');
		} else {
			require( __DIR__ . '/../view/editEvent.php');
		}
	}
	
	/**
	 * Add an event
	 */
	public function saveEvent()
	{
		
		//var_dump($_POST);
		
		$Event = Factory::createModel('Event');
		$Event->setData($_POST, true); // maps post vars to model properties
		$Event->user_id = $this->User->getId();
		
		$successMsg = 'Event Saved!';
		if ($Event->getId()) {
			$successMsg = 'Event Updated!';
		}
		
		if (!$Event->save()) {
			throw Exception('Could not save event.');
		}
		
		$this->App->redirect($this->App->getReturnUrl('?app=calendar'), $successMsg);
	}
	
	/**
	 * Delete an event
	 */
	public function deleteEvent()
	{
		
		if (!$id = $this->Req->get('id')) {
			throw new Exception('Invalid event');
		}
		
		$Event = Factory::createModel('Event');
		$Event->findById($id);
		
		if ($Event->user_id != $this->User->getId()) {
			throw new Exception('Invalid User');
		}
		
		if ($Event->delete() === false) {
			throw new Exception('Could not delete event.');
		}
		
		$this->App->redirect($this->App->getReturnUrl('?app=calendar'), 'The event has been deleted!');
	}

	public function test()
	{
		$Event = Factory::createModel('Event');
		$Event->findById(43);
		
		var_dump($Event);
		
		$User = Factory::createModel('User');
		$User->findById(2);
		
		var_dump($User);
		
		var_dump($User->id);
		var_dump($User->getId());
		
		$User = Factory::getUser();
		var_dump($User);
		
		var_dump($User->id);
		var_dump($User->getId());
		
		
		
	}


}
