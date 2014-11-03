Fiji Cloud Webmail
==================

Fiji Cloud Webmail is a beautiful open source webmail client supporting the latest IMAP4 protocol. 

Fiji Cloud Webmail is developed because the current options of webmail clients is not that great. We aim to deliver a high end webmail client that you can integrate into your website and have your email users use with minimal configuration. 

http://www.fijicloudmail.com/

**Status**

The app is in rapid development and will be broken most the time. 

*Install*

You can either use composer or install the required libraries manually.

***Using Composer***

```
git pull https://github.com/fijiwebdesign/fijiwebmail
cd fijiwebmail
php composer.phar install
```

or `composer install` if you have composer installed globally.
Composer install and usage: https://getcomposer.org/ 

***Manually include Zend Framework***

Download Zend Framework 2.x from http://framework.zend.com/
Edit config/App.php 
Edit the line: `public $zendPath = '/var/lib/zf2/library/Zend/';` to reflect your Zend Framework library path. 

**Admin Login**

When you first view Fiji Webmail in the browser you will be asked to log in. Use the credentials:
Username: `admin`
Password: `admin`

**Secure your application**

By default, Fiji Webmail created a `.fiji_webmail.sqlite` database file in your fijiwebmail root. This is so you can log in and play around. 

In order to use Fiji Webmail in production, you have to setup a MySQL Database. Then edit `config\Service.php`. Update the settings so you can connect to your database. 

Then go to `fijiwebmail/install/install.php` to create your default data. 












