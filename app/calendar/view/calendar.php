<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
 
$this->Doc->title = "Calendar";
?>

<!-- Grid row -->
<div class="row-fluid">

    <!-- Example jQuery FullCalendar -->
    <!-- Data block -->
    <article class="span12 data-block">
        <div class="data-container">
            <header>
                <h2>Calendar</h2>
                <ul class="data-header-actions tabs">
                    <li class="demoTabs active"><a href="#basic">My Calendar</a></li>
                    <li class="demoTabs"><a href="#google">Holidays</a></li>
                </ul>
            </header>
            <section class="tab-content">
            
                <!-- Tab #basic -->
                <div class="tab-pane active" id="basic">
                    <div class='fullcalendar fullcalendar-mine'></div>
                </div>
                <!-- /Tab #basic -->
                
                <!-- Tab #google -->
                <div class="tab-pane" id="google">
                    <div class='fullcalendar fullcalendar-gcal'></div>
                </div>
                <!-- /Tab #google -->
                
            </section>
        </div>
    </article>
    <!-- /Data block -->
    
</div>
<!-- /Grid row -->


<script>
    $(document).ready(function(){
        
        
        // Tabs
        $('.demoTabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
            $('.fullcalendar-demo, .fullcalendar-gcal').fullCalendar('render'); // Refresh jQuery FullCalendar for hidden tabs
        })
        
    });
</script>

<!-- jQuery FullCalendar -->
<script src="templates/chromatron/js/plugins/fullCalendar/jquery.fullcalendar.min.js"></script>
<!-- Google Calendar plugin for jQuery FullCalendar -->
<script src="templates/chromatron/js/plugins/fullCalendar/gcal.js"></script>
<!-- JSON Parser -->
<script src="public/js/jquery.json.js"></script>

<script>
    $(document).ready(function() {
    
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        
        var events;
        
        // parses a timestamp into date parts
		function parseTimestamp(timestamp) {
    		var date = new Date(timestamp);
    		var d = date.getDate();
			var m = date.getMonth() + 1;
			var y = date.getFullYear();
			var h = date.getHours();
			var i = date.getMinutes();
			var ampm = 'am';
			if (h > 12) {
				ampm = 'pm';
				h = h - 12;
			}
			h = new String(h).length == 1 ? '0' + h : h;
			i = new String(i).length == 1 ? '0' + i : i;
			return {d: d, m: m, y: y, h: h, i: i, ampm: ampm};
    	}
        
        function loadMyCal(events) {
        	window.cal = $('.fullcalendar-mine').fullCalendar({
	            header: {
	                left: 'title',
	                center: '',
	                right: 'today month,basicWeek prev,next'
	            },
	            buttonText: {
	                prev: '<span class="awe-arrow-left"></span>',
	                next: '<span class="awe-arrow-right"></span>'
	            },
	            editable: false,
	            events: events,
	            // @todo use eventMouseover instead
	            eventAfterRender: function(event, element) {
			        $(element).attr('title', event.title);
			        var startDate = parseTimestamp(event.start.getTime());
			        var startTime = startDate.h + ':' + startDate.i + startDate.ampm;
			        var endDate = parseTimestamp(event.end.getTime());
			        var endTime = endDate.h + ':' + endDate.i + endDate.ampm;
			    	$(element).attr('data-content', event.description 
			    	+ (event.location ? '<hr /><b>Venue</b> ' + event.location : '')
			    	+ '<br/><b>Starts</b> ' + startTime + ' to ' + endTime);
			    	$(element).popover({
			    		trigger: 'hover',
			    		placement: 'top'
			    	})
			    },
			    // @todo fix editable:true as it errors and stops these jsEvents firing
			    eventClick: function(calEvent, jsEvent, view) {
			    	
			    	showEventDetails('?app=calendar&view=editEvent&id=' + calEvent.id);
			
			    },
			    eventMouseover: function(calEvent, jsEvent, view) {
			    	
			    },
			    eventDayclick: function() {
			    	console.log('dayclick', arguments)
			    },
			    eventDayClick: function() {
			    	console.log('dayclick', arguments)
			    }
	        });
        }
        
        /**
         * Load Calendar from events. 
         * @todo use URLs since they have pagination and get events from calendar API 
         */
        $.ajax('?app=calendar&view=events', {
        	success: function(json) {
        		loadMyCal($.evalJSON(json));
        	}
        });
        
        $('.fullcalendar-gcal').fullCalendar({
            header: {
                left: 'title',
                center: '',
                right: 'today month,basicWeek prev,next'
            },
            buttonText: {
                prev: '<span class="awe-arrow-left"></span>',
                next: '<span class="awe-arrow-right"></span>'
            },
            events: {
                url: 'http://www.google.com/calendar/feeds/usa__en%40holiday.calendar.google.com/public/basic',
                className: 'gcal-event', // an option!
                currentTimezone: 'America/Chicago' // an option!
            }
        });
        
        // calendar details modal
        window.showEventDetails = function(url) {
        	//event.preventDefault();
        	$('#event-details').modal('show');
        	$('#event-full-view').attr('href', url);
        	
        	loadEventDetailsUrl(url);
        	
        }
        
        function loadEventDetailsUrl(url) {
        	$.ajax(url + '&siteTemplate=ajax', {
        		complete: function(xhr) {
        			var html = xhr.responseText;
	        		$('#event-details-body').html(html)
	        	}
        	});
        }
        
        // submit form when we click modal
        $('#btn-update-event').bind('click', function() {
        	$('#btn-send-event').click();
        });
        
        // open edit event in modal
        $('#side-note-compose-event .btn-compose').click(function(event) {
        	event.preventDefault();
        	showEventDetails('?app=calendar&view=editEvent');
        })
        
    });
</script>

<div class="modal hide fade" id="event-details">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <header class="btn-group open" id="compose-select">
        <h3 id="event-details-title" data-toggle="dropdown">Event Details</h3>
        <a href="#" data-toggle="dropdown" class="caret"></a>
        <ul class="dropdown-menu">
            <li><a id="event-full-view" href="?app=calendar&view=editEvent">Event Details Full View</a></li>
        </ul>
    </header>
  </div>
  <div class="modal-body">
    <p id="event-details-body"></p>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-alt btn-primary" id="btn-update-event" type="submit"><i class="awe-plane"></i>&nbsp;Save Event</button>
    <a href="#" data-dismiss="modal" class="btn"><i class="awe-remove"></i> Close</a>
  </div>
</div>
<style type="text/css">
#event-details {
	/** @todo only if screen is small 
	position: absolute; */
}
#event-details .form-actions {
	display: none;
}
#event-details fieldset {
	padding: 15px;
	width: auto;
}
#event-details .modal-body {
	overflow-x: hidden;
}
.fullcalendar-mine .fc-event {
	cursor: pointer;
}
.popover b {
	display: inline-block;
	width: 50px;
}
</style>

<!-- jQuery FullCalendar Styles -->
<link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/jquery.fullcalendar.css'>