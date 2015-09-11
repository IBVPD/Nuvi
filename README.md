Overview
========

This project has three dependencies that are needed for full functionality. Beanstalkd, a RDBM such as MySQL and composer.
Composer is used to install the project's php bundle dependencies. The instructions assume a RedHat/Fedora based system using
apache.

Install Composer
================

Download and install composer via php. We usually install this to a globally accessible directory such as /usr/local/bin.
Often composer has issues with consuming lots of memory when running an update. Or speed issues due to php garbage
collection. As such we install a wrapper script. Create a bash shell script in /usr/local/bin/composer with the following
contents.

```shell
#!/bin/sh

/usr/bin/php -dmemory_limit=1G -dzend.enable_gc=0 /usr/local/bin/composer.phar "$@"
```

Then install composer by running

```shell
curl https://getcomposer.org/composer.phar -o /usr/local/bin/composer.phar
```

Keep composer up to date by periodically running the self-update command option (~every 30 days).

```shell
composer self-update
```

Install Beanstalkd
==================

If using CentOS or a Fedora based distro beanstalkd the package is part of the distro packaging tools.

```shell
yum install beanstalkd
```

By default beanstalkd will listen on all interfaces on port 11300. It has no concept of user or authentication. You can
change this by editing the BEANSTALK_ADDR line in '/etc/sysconfig/beanstalkd'.

```shell
...
BEANSTALKD_ADDR=0.0.0.0
...
```

Change the BEANSTALKD_ADDR line to whatever interface. If on the same machine as the webserver change it to 127.0.0.1.
Enable and start the service.

```shell
chkconfig --level 345 beanstalkd on
service beanstalkd start
```

Initial Project Install
======================

Clone the project via git

```shell
git clone https://gitlab.noblet.ca/who/nuvi.git /var/www/local/nuvi
```

Install the dependencies. 
```shell
composer install -o
```

On first run of this command or any time new parameters are introduced, it will ask a number of setup questions. Such as
the DB type, username, password etc. One of the important parameter it asks is the 'secret'. It is used for form CSRF 
protection. It can be set to anything but should be kept private. We often use the output of the uuidgen command available
on unix servers.

Initiate the database
```shell
app/console doctrine:database:create
app/console doctrine:schema:create
```


Configure cronjob
-------------------

The importation system runs in the background by talking with the beanstalk server. The command checks for a job in the queue. 
When it finds one or more it will continue to run in batches until all jobs are complete so there is no need to have a short
interval. We recommend running it every hour. To avoid file permission issues, it should be run as the same user the websever
runs as in the project root. The command to run is

```shell
app/console nsimport:run-batch
```
which will output the jobs it is working on and will return when complete.


Configure Apache
================

An example apache virtual host configuration is included below. The API endpoint requires and enforces an SSL connection
to function.

```
<VirtualHost *:443>
  ServerName nuvi

  DocumentRoot "/var/www/local/nuvi/web"
  <Directory "/var/www/local/nuvi/web">
    Options Indexes FollowSymLinks MultiViews
    AllowOverride None
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ /app.php [QSA,L]
    </IfModule>
    Require all granted
  </Directory>

  SSLEngine on
  SSLCertificateFile /etc/pki/tls/certs/server.crt
  SSLCertificateKeyFile /etc/pki/tls/private/server.key
  SSLCertificateChainFile /etc/pki/tls/certs/server-bundle.crt

  SSLProtocol -ALL +TLSv1 +TLSv1.1 +TLSv1.2
  SSLHonorCipherOrder On
  SSLCipherSuite ECDH+AESGCM:DH+AESGCM:ECDH+AES256:DH+AES256:ECDH+AES128:DH+AES:ECDH+3DES:DH+3DES:RSA+AESGCM:RSA+AES:RSA+3DES:!aNULL:!MD5:!DSS
  Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"

  SSLOptions StrictRequire
  SetEnvIf User-Agent ".*MSIE.*" nokeepalive ssl-unclean-shutdown
</VirtualHost>
```

If behind a proxy there are additional configuration steps to follow [here](http://symfony.com/doc/current/cookbook/request/load_balancer_reverse_proxy.html)

Updates / Moving between releases
=================================

Run 'git fetch origin' periodically to update the repository without changing the local copy of the project. Releases 
are marked via git tags. Viewing all releases is possible by running 'git tag -l' within the project root which will 
output
```shell
[gnat@iridium nuvi]$ git tag -l
0.1.0
0.1.1
2014-01-27
2014-10-17
```

For example to move the project to the 0.1.1 version run.
```shell
git checkout 0.1.1
composer install -o
```

Update the database schema. You can check what
```shell
 app/console doctrine:schema:update --force
```

If you'd like to check what changes will occur prior to making the changes you can run
```shell
 app/console doctrine:schema:update --dump-sql
```

Running Tests
=============
There are a number of unit and functional tests. They require a database and some fixtures. Setup database access rights
and configure the app/config/parameters_test.yml to match.

Load the fixtures
```shell
app/console doctrine:database:drop --env=test --force
app/console doctrine:database:create --env=test
app/console doctrine:schema:create --env=test
app/console doctrine:fixtures:load --env=test -n
```
Then run the tests
```shell
-sh-4.1$ ./bin/phpunit -c app/
PHPUnit 4.8.6 by Sebastian Bergmann and contributors.

...............................................................  63 / 455 ( 13%)
............................................................... 126 / 455 ( 27%)
............................................................... 189 / 455 ( 41%)
............................................................... 252 / 455 ( 55%)
............................................................... 315 / 455 ( 69%)
............................................................... 378 / 455 ( 83%)
............................................................... 441 / 455 ( 96%)
..............

Time: 20.26 seconds, Memory: 231.50Mb

OK (455 tests, 2197 assertions)
```

API Documentation
=================

API documentation is available [here](src/NS/ApiBundle/README.md)
