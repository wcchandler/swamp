murmur
======

**murmur** -- a stupid name for a stupid web app...

demo: http://apps.0x0f.io/murmur/?conf=demo

## *what is murmur?*
a stupid pinging web app with the following features:

* instance based

* passive

* stupid

*instance based*
    **-** the pings and port checks only run when the application is loaded.  this promotes greater flexibility with multiple configurations

*passive*
    **-** there is no daemon running in the background that has to be restarted or re-HUPed.  every change made to the backend gets imported instantly on the next run.  there is also no extra unneccessary polling unless you specify it in the config

*stupid*
    **-** i can't really stress this enough.  the web app is stupid.  it doesn't try to do more than it needs.  it doesn't try to assume something messed up.  it just blindly does stuff until it fails.  then you mash F5 and hope it works the next time around.

## *screenshots*
![first screenshot](https://i.imgur.com/ambQS4G.png "first demo screenshot")

## *dependencies*

* http server that can run php scripts (e.g. apache, httpd)
* php
* php-SimpleXML
* php-JSON

## *get it running*

* install apache or whatever
* cd /var/www/html
* git clone https://github.com/wcchandler/murmur.git
* point browser to wherever you just put it

## *config*
there's support for multiple configs.  just cp configs/test.xml configs/whatever.xml and start editing.  i tried to include enough comments for it to be intuitive

## *keep it stupid*
the main point of this web app is to keep everything stupidly simple.  don't overcomplicate things.  if you need *more* then you should probably look at actual monitoring systems.

## *working environments*
* ubuntu 14.04 droplet on digital ocean http://apps.0x0f.io/murmur/
* pidora release (obviously on a raspberry pi) - http://home.0x0f.io/murmur/


