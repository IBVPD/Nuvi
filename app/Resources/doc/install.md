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


Initial Project Install
======================

Clone the project via git

```shell
git clone https://github.com/IBVPD/Nuvi.git /var/www/local/nuvi
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
bin/console doctrine:database:create
bin/console doctrine:schema:create
```

Configure cronjob
-------------------

The importation system runs in the background by talking with the beanstalk server. The command checks for a job in the queue
and will run that one job. It will process the batch amount and then exit. Therefore we recommend a low time differential between
 runs. Something between 2-5 minutes. To avoid file permission issues, it should be run as the same user the websever
runs as in the project root. The command to run is.

```shell
bin/console nsimport:run-batch
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
    DirectoryIndex app.php
    AllowOverride None
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ /app.php [QSA,L]
    </IfModule>
    Require all granted
    <LimitExcept POST GET PATCH PUT>
      Deny from all
    </LimitExcept>
  </Directory>
  <Directory "/var/www/local/nuvi/web/bundles">
    <IfModule mod_rewrite.c>
        RewriteEngine Off
    </IfModule>
  </Directory>
  
  <Directory "/var/www/local/nuvi/app">
    deny from all
  </Directory>

  <Directory "/var/www/local/nuvi/src">
    deny from all
  </Directory>
  
  # used for determining if the user has gone offline
  <Location /offline-check>
    RedirectMatch 204 .*
    Header set Cache-Control "private, no-cache, no-store, proxy-revalidate, no-transform"
    Header set Pragma "no-cache"
  </Location>

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

Configure PHP
=============
It is recommended to change the following settings in /etc/php.ini

- memory_limit: Somewhere between 256 and 512M (our VM is set to 512M).
- post_max_size: 20-30M
- upload_max_filesize: 10-30M

Moving between releases
=================================

Run 'git fetch origin' periodically to update the repository without changing the local copy of the project. Releases 
are marked via git tags. Viewing all releases is possible by running 'git tag -l' within the project root which will 
output.

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

Updates
=======
Whenever you move between releases or pull a new version it is prudent to run a couple of commands.

```shell
composer install -o

```

If you'd like to check what changes will occur prior to making the changes you can run.

```shell
 bin/console doctrine:migrations:migrate
```

It will ask you if you want to apply the updates to the database.

Automatically Updating
======================

We typically use ansible to handle deployments.

```shell
ansible-playbook -i app/config/hosts.ini -e target=SERVERNAME app/config/deploy.yml
```

The deploy.yml file follows the [servergrove.symfony2](https://github.com/servergrove/ansible-symfony2) ansible playbook 
and is currently setup to deploy to our demo instance. Creating a copy of this and modifying it for your internal use 
(adjusting paths etc) would allow you to deploy new versions automatically with the ability to roll back if there are 
issues.


Running Tests
=============
There are a number of unit and functional tests. They require a database and some fixtures. So as to not disturb live data 
create a second database with the same or different access rights. Copy app/config/parameters.yml.dist to 
app/config/parameters_test.yml.  This will allow the system to load fixture data which the tests will run 
against.

Rebuild the test database and load the fixtures.

```shell
bin/console doctrine:database:drop --env=test --force
bin/console doctrine:database:create --env=test
bin/console doctrine:schema:create --env=test
bin/console doctrine:fixtures:load --env=test -n
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
