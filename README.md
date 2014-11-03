Fiji Webmail
============

Fiji  Webmail is a beautiful, modern open source webmail client supporting the latest IMAP4 protocol.

Fiji Webmail aims to deliver a high end webmail client with support for all major email servers that you can integrate into your website and have your email users use with minimal configuration.

**Features**

* Webmail client with modern features such as theaded email conversations, custom labels or tags, custom folders
* Address Book of all contacts found in emails
* Calendar and Tasks lists
* Boostrap templates for full customization of all Apps

**Webmail Customization**

It would be an alternative to PHP email client such as RoundCube, SquirelMail or Horde Mail with an aim to deliver modern features and provide more flexibility and easy customization. It uses Boostrap templates which allow you to fully customize the theme. 

http://www.fijicloudmail.com/

**Status**

The app is in rapid development and is unstable. Appropriate for development only.

Installation
------------

Clone the repository to get the latest development code or download a release from the releases. Fiji Webmail depends on Zend Framework 2. You can download these dependencies using Composer. Alternatively you can manually install Zend Framework and set it's location in the Fiji Webmail App config file. 

***Install Using Composer***

```
git clone https://github.com/fijiwebdesign/fijiwebmail
cd fijiwebmail
php composer.phar install
```

Use `composer install` if you have composer installed globally. Composer install and usage: https://getcomposer.org/

***Manually Install***

* `git clone https://github.com/fijiwebdesign/fijiwebmail` or download a stable release
* Download Zend Framework 2.x from http://framework.zend.com/
* Edit the Fiji Webmail main config file: `config/App.php`. 
* Change the line: `public $zendPath = '/var/lib/zf2/library/Zend/';` to reflect your Zend Framework library path.

**Admin Login**

When you first view Fiji Webmail in the browser you will be asked to log in. Use the credentials:

* Username: `admin` 
* Password: `admin`

*Change this password by going to your user settings (link next to your avatar)*

**Secure your application**

By default, Fiji Webmail created a `.fiji_webmail.sqlite` database file in your fijiwebmail root. This is so you can log in and play around.

In order to use Fiji Webmail in production, you have to setup a MySQL Database. Then edit `config\Service.php`. Update the settings so you can connect to your database.

Then go to `fijiwebmail/install/install.php` to create your default data.
