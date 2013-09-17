<?php

use Fiji\Factory;

$Req = Factory::getRequest();

// event start and end times
$startTime = $Req->get('start');
$endTime= $Req->get('end');

$Event = Factory::createModel('Event');
$EventList = Factory::createModelCollection($Event);

$EventList->search("where start >= ? AND end <= ?", array($startTime, $endTime));

// show events as json response
header('Content-Type: text/javascript');
echo json_encode($EventList);

die;

// example
echo json_encode(array(
	array(
		'title' => "All DAy Event", 
		'start' => date(DATE_RSS),
		'description' => 'Fun all day Event!',
		'allDay' => true
	)
));

?>