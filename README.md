NagStrap
=========

Nagios interface that looks a little more appealing with the use of Bootstrap.

Installation - Interface
=====================

* Download the zip.
* Unzip it in a Apache DocumentRoot.
* Edit line 6 in the index.php to point to your Nagios server using your credentials (please note that this will make the interface available without the use of any credentials)
* Run `composer install`
* ...
* Profit

Note that you'll need to add the statusJson.php file on the Nagios server, see below.

Installation - Server Setup
============

For status information, NagStrap uses statusJson.php: https://github.com/lizell/php-nagios-json

Installation is really simple:

* Find the folder that has your Nagios index.php in it (generally something like /usr/share/nagios/) and download a copy of statusJson.php, like so:

		`curl 'https://raw.githubusercontent.com/lizell/php-nagios-json/master/statusJson.php' > statusJson.php`

* Edit the top of statusJson.php to have the correct path to your Nagios status.dat file
