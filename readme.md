## Pushman

[![Latest Stable Version](https://poser.pugx.org/pushman/pushman/v/stable)](https://packagist.org/packages/pushman/pushman) [![Total Downloads](https://poser.pugx.org/pushman/pushman/downloads)](https://packagist.org/packages/pushman/pushman) [![Latest Unstable Version](https://poser.pugx.org/pushman/pushman/v/unstable)](https://packagist.org/packages/pushman/pushman) [![License](https://poser.pugx.org/pushman/pushman/license)](https://packagist.org/packages/pushman/pushman)

Pushman is an open source web socket event manager. It allows you to push events over HTTP to your own Pushman server which will push event information down to a client via a Web Socket.

You can bind server side events to client notifications.

Demo on the [Pushman Website](http://pushman.dfl.mn).

## Todo for Version 2.4
* User specific broadcasting
  * Associate a connection id with a users ID to allow broadcasting on channels to specific ids.
* Shad is working on a cool new User Interface

## Todo for Version 3
*I'm really sorry I pushed test writing back to V3, it is a top priority but I need to refactor loads of code I wrote hastily to ensure PHPSpec can work. Watch for code refactors marked "preperation for testing" in the future*

* Write tests for PHPLib and Pushman :) Integrate with travis-ci
* Integrate with StyleCI
* Build Pushman Lite as an independant Package to include in sites.
* Way better documentation for building Pushman.

### Long term todo list
* Push console logs out on PushmanInternal Channel. // later
* &lt;script&gt; tags in Payloads are excuted. // fixed but needs more

## Official Documentation
Documentation for usage can be found on the [Pushman website](http://pushman.dfl.mn/documentation).

Documentation for building your own Pushman instance is found below.

### Building your own Pushman Instance
Pushman works fantastically on Laravel's [Forge](http://forge.laravel.com)! **You still need to install prerequisites though.**

#### PHP Extensions
Pushman requires [ZeroMQ](http://zeromq.org/) which is a custom binary (Windows and Linux), along with it's PHP extension.

You can follow zmq's installation instructions, but you should know building it for a Forge server is easy.

##### Step 1 - Install the Binary
*When installing on Windows, you can just install the .exe from [their website](http://zeromq.org/distro:microsoft-windows).*

For Debian based systems, there is an apt package: `apt-get install libzmq3-dev`

[ZeroMQ Download](http://zeromq.org/area:download)

##### Step 2 - Install the PHP Extension
You can always build the PHP extension yourself:
```
git clone git://github.com/mkoppanen/php-zmq.git
$ cd php-zmq
phpize && ./configure
$ sudo make
$ sudo make install
```
*When building the extension for Windows, you can just download an existing .dll file from their website and place it in the php /ext directory. You will also need to add the `extension=zmq.dll` to your php.ini file.*

[PHP Extension Download](http://zeromq.org/bindings:php#toc3)

#### Port Requirements
Pushman requires two ports to function, an `INTERNAL` and a `PUBLIC` port, the public port handles websocket connections and the other handles incoming API requests.

By default both of these ports are configurable in the `.env` file in the root directory or by setting an environment variable. You only need to setup a firewall rule for the `PUBLIC` port, but do ensure both ports are free to bind to.

.env file:
```
PUSHMAN_PORT=8080
PUSHMAN_INTERNAL=5555
```

#### Installing the Code
On forge, you can just build a new site, and give the it the Github repo to install itself. `Duffleman/pushman` on the _master_ branch is what you need to enter.

On a regular server, git clone the directory and run `composer install --no-dev` to install the requirements.

#### Configuration
Pushman requires a database, so for both Forge and a regular server, enter your Database editor of choice, or sqlite, and build a database and enter the details in the `.env` file in the root web directory.

Once the database configuration is set, you can run `php artisan migrate` followed by `php artisan key:generate` to publish the database layout. Or on Forge, just redeploy the site.

**You MUST set an App Key.**

You may also need to run `find storage/* -type d -exec chmod 775 {} \;` to grant write permissions on your storage folder

#### Runtime
Pushman itself can then be run by using `php artisan pushman:run`. I highly recommend setting up a supervisord task for this or in Forge, go into your server tab and enter the full path to artisan and Forge will auto monitor the task for you.

###### Example Command
`php /home/forge/pushman.dfl.mn/artisan pushman:run`

## Security Vulnerabilities
If you discover a security vulnerability within Pushman, please send an e-mail to George Miller at george@duffleman.co.uk. All security vulnerabilities will be promptly addressed.

## License
Pushman is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
