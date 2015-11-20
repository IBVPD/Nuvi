PHP
===================
Download/install Web Platform Installer (Web Pi) https://www.microsoft.com/web/downloads/platform.aspx
(This required adding http://download.microsoft.com/ trusted sites in IE)
Click on 'Products'. 
In Search type PHP and select the following to be installed.
PHP 5.6.x 
    Windows Cache Extenstion 1.3 for PHP 5.6

This will pull in a whole bunch of dependent packages (IIS CGI, URL Rewrite etc...)

*Note: The guide I followed recommended that IIS was *NOT* previously installed via the normal addition of the IIS role however I didn't do that. 

INTL
----
Open IIS Manager, locate the PHP Manager icon.
"Enable or Disable an extension"
Ensure that the intl extension is enabled.

"Manage All Settings":
Add 'date.timezone' and set the value to a valid and logical timezone (for example locally I use America/Edmonton).

Composer
===================

Install Composer:
https://getcomposer.org/Composer-Setup.exe
Run the installer - all defaults seem to be fine.

Git
===================
Download window client - http://git-scm.com/download/win (add https://mysysgit.googlecode.com to trusted site list)
Install and select defaults - except ensure the following:
    Run Git from the windows command prompt
    Checkout Windows-style, commit Unix-style line endings

Configure SQL Server
===================
Create a database (I used 'nuvi')
Add a user (user/password, not windows login) I used nuvi - set the default database to whatever was created above.
Ensure the SQL Server allows for mixed mode authentication.

Install NUVI
===================
Open Power Shell
Navigate to wherever you want to run the app from ( I use C:\inetpub\nuvi, so cd C:\inetpub )

```
git clone https://gitlab.noblet.ca/who/nuvi nuvi
cd nuvi
composer install (If this pauses and asks for github credentials username: nuvisentinel, password: nuvi2014
    database driver is pdo_sqlsrv, (use the username password above)
    secret: Change to something random, a uuid or some other long string.
    the rest should be self-explanatory. 
php app\console doctrine:database:create
php app\console doctrine:schema:create
```

Configure IIS
===================
Ensure that whatever user the PHP CGI/FastCGI app is running as can write to app/cache and app/logs. For my tests I just allowed all "Users" write/modify to that directory.
I did this by going to Authentication-> Set Anonymous = Whatever user is installing the application (in my case nuviadmin)

http://www.iis.net/learn/application-frameworks/install-and-configure-php-applications-on-iis/using-fastcgi-to-host-php-applications-on-iis

SECURITY ISOLATION FOR PHP WEB SITES
------------------------------------

The recommendation for isolating PHP Web sites in a shared hosting environment is consistent with all general security isolation recommendations for IIS. In particular, it is recommended to:

* Use one application pool per Web site
* Use a dedicated user account as an identity for the application pool
* Configure an anonymous user identity to use the application pool identity
* Ensure that FastCGI impersonation is enabled in the php.ini file (fastcgi.impersonate=1)
