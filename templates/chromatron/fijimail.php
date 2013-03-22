<!DOCTYPE html>
<!--[if IE 8]>    <html class="no-js ie8 ie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 ie" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	
<!-- Mirrored from template.walkingpixels.com/chromatron/goodies.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 27 Dec 2012 09:30:09 GMT -->
<head>
		<meta charset="utf-8">
		<title>Fiji Mail | Inbox</title>
		<meta name="description" content="">
		<meta name="author" content="Walking Pixels | www.walkingpixels.com">
		<meta name="robots" content="index, follow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- jQuery Snippet Styles -->
		<link rel='stylesheet' type='text/css' href='css/plugins/jquery.snippet.css'>
		
		<!-- Styles -->
		<link rel='stylesheet' type='text/css' href='css/chromatron-blue.css'>
		
		<!-- Fav and touch icons -->
		<link rel="shortcut icon" href="img/icons/favicon.html">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/icons/apple-touch-icon-114-precomposed.html">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/icons/apple-touch-icon-72-precomposed.html">
		<link rel="apple-touch-icon-precomposed" href="img/icons/apple-touch-icon-57-precomposed.html">
		
		<!-- JS Libs -->
		<script src="../../ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/libs/jquery.js"><\/script>')</script>
		<script src="js/libs/modernizr.js"></script>
		<script src="js/libs/selectivizr.js"></script>
		
		<script>
			$(document).ready(function(){
				
				// Tooltips for nav badge
				$('.main-navigation .badge').tooltip({
					placement: 'bottom'
				});
				
				// Tooltips for widgets
				$('.widget [title]').tooltip({
					placement: 'left'
				});
				
				// Tooltips for content
				$('.content-block [title]').tooltip({
					placement: 'top'
				});
				
				// Close button for widgets
				$('.widget').alert();
				
				// Remove tooltip when widget is closed
				$('.widget').bind('close', function () {
					$(this).find('.close').tooltip('destroy');
				})
				
				// Tabs
				$('.demoTabs a').click(function (e) {
					e.preventDefault();
					$(this).tab('show');
				})
				
				// Tickets
				$('#ticketsDemo .ticket-data-activity a').click(function (e) {
					e.preventDefault();
				})
				
				// Smart Wizard
				$('#wizard').smartWizard();
				
			});
		</script>
		
		<script type="text/javascript">
			//var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-22557155-2"]);_gaq.push(["_trackPageview"]);(function(){var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();
		</script>
		
		<style type="text/css">
		    
		    
		    
		    
		</style>
		
		
	</head>
	<body>
		
		<!-- Main page container -->
		<div class="container-fluid">
		
			<!-- Left (navigation) side -->
			<section class="navigation-block">
			
				<!-- Main page header -->
				<header>
				
					<!-- Main page logo -->
					<h1><a class="brand" href="login.html" title="Back to Homepage">Chromatron Responsive Admin Backend built with Twitter Bootstrap</a></h1>
					
					<!-- Main page headline -->
					<p>A cathode ray tube awesomeness</p>
					
				</header>
				<!-- /Main page header -->
				
				<!-- User profile -->
				<section class="user-profile">
					<figure>
						<img alt="John Pixel avatar" src="http://placekitten.com/50/50">
						<figcaption>
							<strong><a href="#" class="">John Pixel</a></strong>
							<em>Administrator</em>
							<ul>
								<li><a class="btn btn-primary btn-flat" href="#" title="View www.example.com">view website</a></li>
								<li><a class="btn btn-primary btn-flat" href="#" title="Securely logout from application">logout</a></li>
							</ul>
						</figcaption>
					</figure>
				</section>
				<!-- /User profile -->
				
				<!-- Responsive navigation -->
				<a href="#" class="btn btn-navbar btn-large" data-toggle="collapse" data-target=".nav-collapse"><span class="fam-heart"></span> Goodies</a>
				
				<!-- Main navigation -->
				<nav class="main-navigation nav-collapse" role="navigation">
					<ul>
						<li><a href="index.html" class="no-submenu"><span class="fam-house"></span>Dashboard</a></li>
						<li><a href="forms.html" class="no-submenu"><span class="fam-application-form"></span>Forms</a></li>
						<li><a href="charts.html" class="no-submenu"><span class="fam-chart-line"></span>Charts</a></li>
						<li><a href="tables.html" class="no-submenu"><span class="fam-application-view-columns"></span>Tables</a></li>
						<li>
							<a href="#"><span class="fam-picture"></span>Gallery<span class="badge" title="5 new image uploaded">5</span></a>
							<ul>
								<li><a href="gallery.html">Car Gallery</a></li>
								<li><a href="gallery.html">Food Gallery</a></li>
								<li><a href="gallery.html">Art Gallery</a></li>
								<li><a href="gallery.html">Animal Gallery</a></li>
								<li><a href="gallery.html">Super long name to see how it collapse</a></li>
							</ul>
						</li>
						<li><a href="file-explorer.html" class="no-submenu"><span class="fam-briefcase"></span>File explorer</a></li>
						<li><a href="calendar.html" class="no-submenu"><span class="fam-calendar-view-day"></span>Calendar<span class="badge" title="27 tasks this week">27</span></a></li>
						<li><a href="ui-buttons.html" class="no-submenu"><span class="fam-rosette"></span>UI & Buttons</a></li>
						<li><a href="typo.html" class="no-submenu"><span class="fam-text-padding-left"></span>Typography</a></li>
						<li><a href="grid.html" class="no-submenu"><span class="fam-cog"></span>Grid</a></li>
						<li class="current">
							<a href="#"><span class="fam-heart"></span>Goodies</a>
							<ul>
								<li><a href="goodies.html">Goodies</a></li>
								<li><a href="401.html">Error 401</a></li>
								<li><a href="403.html">Error 403</a></li>
								<li><a href="404.html">Error 404</a></li>
								<li><a href="500.html">Error 500</a></li>
								<li><a href="503.html">Error 503</a></li>
							</ul>
						</li>
					</ul>
				</nav>
				<!-- /Main navigation -->
				
				<!-- Side note with nested style -->
				<section class="side-note nested">
					<h2>Nested side note</h2>
					<p>Lorem ipsum dolor sit amet, conse ctetur adipiscing elit. Maec enas id augue ac metu aliquam.</p>
					<p>Sed pharetra placerat est suscipit sagittis. Phasellus <a href="#">aliquam</a> males uada blandit. Donec adipiscing sem erat.</p>
				</section>
				<!-- /Side note with nested style -->
				
				<!-- Side note -->
				<section class="side-note">
					<h2>Clean side note</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et dignissim metus.</p>
					<p class="separator">Maecenas id augue ac metus tempus aliquam. Sed pharetra placerat est suscipit sagittis.</p>
					<p class="separator">Phasellus aliquam malesuada blandit. Donec adipiscing sem erat.</p>
					<a class="btn btn-flat btn-primary pull-right" href="#" title="This is my title!">Read more</a>
				</section>
				<!-- /Side note -->
				
				<!-- Side note -->
				<section class="side-note">
					<div class="thumbnail">
						<img src="http://placekitten.com/221/120" alt="Sample Image">	
					</div>
					<h2>Side note with image</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et dignissim metus. Maecenas id augue ac metus tempus aliquam. Sed pharetra placerat est suscipit sagittis. Phasellus aliquam malesuada blandit. Donec adipiscing sem erat.</p>
					<a class="btn pull-right" href="#" title="This is my title!">Event details</a>
				</section>
				<!-- /Side note -->
				
				<!-- Side note with separator -->
				<section class="side-note separator">
					<h2>Side note with separator</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et dignissim metus. Maecenas id augue ac metus tempus aliquam. Sed pharetra placerat est suscipit sagittis.</p>
					<ul>
						<li><strong>Sed pharetra placerat</strong></li>
						<li><em>Phasellus aliquam malesuada</em></li>
						<li>Maecenas id augue</li>
						<li>Consectetur <a href="#">adipiscing</a> elit</li>
						<li>Lorem ipsum dolor</li>
					</ul>
				</section>
				<!-- /Side note with separator -->
				
			</section>
			<!-- /Left (navigation) side -->
			
			<!-- Right (content) side -->
			<section class="content-block" role="main">
			
				<!-- Widget container -->
				<div class="widgets-container">
				
					<!-- Widget box -->
					<div class="widget alert fade in increase">
						<a href="#" class="close" data-dismiss="alert" title="Hide widget">&times;</a>
						<span>increase</span>
						<p><strong>+35,18<sup>%</sup></strong> +2489 new visitors</p>
					</div>
					<!-- /Widget box -->
					
					<!-- Widget box -->
					<div class="widget alert fade in decrease">
						<button class="close" data-dismiss="alert" type="button" title="Hide widget">&times;</button>
						<span>decrease</span>
						<p><strong>-12,50<sup>%</sup></strong> -311 new orders</p>
					</div>
					<!-- /Widget box -->
					
					<!-- Widget box -->
					<div class="widget alert fade in increase">
						<a href="#" class="close" data-dismiss="alert" title="Hide widget" >&times;</a>
						<span>7</span>
						<p><strong>Tasks</strong> +3 New Tasks</p>
					</div>
					<!-- /Widget box -->
					
					<!-- Widget Box -->
					<div class="widget alert fade in text-only">
						<button class="close" data-dismiss="alert" type="button" title="Hide widget">&times;</button>
						<p><strong>Text Only App</strong> +29 Lorem Ipsum</p>
					</div>
					<!-- /Widget box -->
					
					<!-- Add new widget box -->
					<div class="widget add-new-widget">
						<a href="#">
							<strong><span class="awe-plus-sign"></span> Add Widget</strong>
						</a>
					</div>
					<!-- /Add new widget box -->
					
				</div>
				<!-- /Widget container -->
				
				<!-- Breadcrumbs -->
				<ul class="breadcrumb">
					<li><a href="#"><span class="awe-home"></span> Home</a></li>
					<li><a href="#">Chromatron template</a></li>
					<li class="active">Goodies</li>
				</ul>
				<!-- Breadcrumbs -->
				
				<!-- Grid row -->
				<div class="row-fluid">
				
				<article class="span1 data-block">
                        <div class="data-container">
                            <header>
                                <h2>Fiji Webmail</h2>
                            </header>
                            <section>
                                <ul class="stats">
                                    
                                    <?php foreach($message as $message) : ?>
                                    <li>
                                        <strong class="stats-count">17</strong>
                                        <p>New registrations</p>
                                        <a class="btn btn-alt btn-primary stats-view" href="#" data-original-title="View new registrations">View</a>
                                    </li>
                                    <?php endif; ?>
                                    
                                </ul>
                            </section>
                        </div>
                    </article>
				
				
				
				</div>
				<!-- /Grid row -->
			
			</section>
			<!-- /Right (content) side -->
			
		</div>
		<!-- /Main page container -->
		
		<!-- Scripts -->
		<script src="js/navigation.js"></script>
		
		<!-- Bootstrap scripts -->
		<!--
		<script src="js/bootstrap/bootstrap-tooltip.js"></script>
		<script src="js/bootstrap/bootstrap-dropdown.js"></script>
		<script src="js/bootstrap/bootstrap-tab.js"></script>
		<script src="js/bootstrap/bootstrap-button.js"></script>
		<script src="js/bootstrap/bootstrap-alert.js"></script>
		<script src="js/bootstrap/bootstrap-popover.js"></script>
		<script src="js/bootstrap/bootstrap-collapse.js"></script>
		<script src="js/bootstrap/bootstrap-modal.js"></script>
		<script src="js/bootstrap/bootstrap-transition.js"></script>
		-->
		<script src="js/bootstrap/bootstrap.min.js"></script>
		
		<!-- Block TODO list -->
		<script>
			$(document).ready(function() {
				
				$('.todo-block input[type="checkbox"]').click(function(){
					$(this).closest('tr').toggleClass('done');
				});
				$('.todo-block input[type="checkbox"]:checked').closest('tr').addClass('done');
				
			});
		</script>
		
		<!-- jQuery Snippet -->
		<script src="js/plugins/snippet/jquery.snippet.min.js"></script>
		
		<script>
			$(document).ready(function() {
			
				$('pre.html').snippet('html',{style:"rand01"});
			
			});
		</script>

		<script type="text/javascript" src="js/plugins/smartWizard/jquery.smartWizard-2.0.js"></script>
		
	</body>

<!-- Mirrored from template.walkingpixels.com/chromatron/goodies.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 27 Dec 2012 09:30:10 GMT -->
</html>
