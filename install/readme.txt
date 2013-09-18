Installation instructions
=========================

Requires:
---------

PHP5.3+
MySQL5+
Apache or similar web server

Install
-------

If on windows, the examples are for WAMP which easily installs Apache and MySQL

Copy the framework files to a web accessible directory. eg: c://wamp/www/fijiframework/
Install Zend Frameowrk 2 from http://framework.zend.com/
Edit config/App.php so $zendPath points to the zend framework library
Edit config/Mail.php to point to your IMAP host and SMTP host. 

That is it! Your webmail should work from here. 


Install the Gallery sample App
------------------------------

Create a database table for your application. 
Edit config/Service.php with your database configuration. 
If you use service\DataProvider\RedBean\RedBean as your dataProvider (config\Service->dataProvider)
	then you only need to create the database named in config\Service->database
	The database will automatically be populated. 
If you use the service\DataProvider\MySql as your dataProvider then:
	Look in install/sql/ for sql files to populate the sample gallery application
	Export the SQL file to your MySQL database (PHPMyAdmin can help)
Now visit the URL of the framework in your browser. eg: http://localhost/fijiframework/
