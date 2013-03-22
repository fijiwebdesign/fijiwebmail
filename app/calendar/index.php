<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
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
                    <li class="demoTabs active"><a href="#basic">Basic</a></li>
                    <li class="demoTabs"><a href="#google">Google</a></li>
                </ul>
            </header>
            <section class="tab-content">
            
                <!-- Tab #basic -->
                <div class="tab-pane active" id="basic">
                    <div class='fullcalendar fullcalendar-demo'></div>
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
        
        <script src="templates/chromatron/js/bootstrap/bootstrap.min.js"></script>
        
        <!-- jQuery FullCalendar -->
        <script src="templates/chromatron/js/plugins/fullCalendar/jquery.fullcalendar.min.js"></script>
        
        <!-- Google Calendar plugin for jQuery FullCalendar -->
        <script src="templates/chromatron/js/plugins/fullCalendar/gcal.js"></script>
        
        <script>
            $(document).ready(function() {
            
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                
                $('.fullcalendar-demo').fullCalendar({
                    header: {
                        left: 'title',
                        center: '',
                        right: 'today month,basicWeek prev,next'
                    },
                    buttonText: {
                        prev: '<span class="awe-circle-arrow-left"></span>',
                        next: '<span class="awe-circle-arrow-right"></span>'
                    },
                    editable: true,
                    events: [
                        {
                            title: 'All Day Event',
                            start: new Date(y, m, 1)
                        },
                        {
                            title: 'Long Event',
                            start: new Date(y, m, d-5),
                            end: new Date(y, m, d-2)
                        },
                        {
                            id: 999,
                            title: 'Repeating Event',
                            start: new Date(y, m, d-3, 16, 0),
                            allDay: false
                        },
                        {
                            id: 999,
                            title: 'Repeating Event',
                            start: new Date(y, m, d+4, 16, 0),
                            allDay: false
                        },
                        {
                            title: 'Meeting',
                            start: new Date(y, m, d, 10, 30),
                            allDay: false
                        },
                        {
                            title: 'Lunch',
                            start: new Date(y, m, d, 12, 0),
                            end: new Date(y, m, d, 14, 0),
                            allDay: false
                        },
                        {
                            title: 'Birthday Party',
                            start: new Date(y, m, d+1, 19, 0),
                            end: new Date(y, m, d+1, 22, 30),
                            allDay: false
                        },
                        {
                            title: 'Walking Pixels website',
                            start: new Date(y, m, 28),
                            end: new Date(y, m, 29),
                            url: 'http://www.walkingpixels.com/'
                        }
                    ]
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
                
            });
        </script>


        <!-- jQuery FullCalendar Styles -->
        <link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/jquery.fullcalendar.css'>