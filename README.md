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
    i have this running on a basic ubuntu 14.04 LA~~M~~P stack on digital ocean.  i also have it running on the latest pidora release[20] (obviously on a raspberry pi)

## *get it running*
* install apache or whatever
* cd /var/www/html
* git clone https://github.com/wcchandler/murmur.git
* point browser to wherever you just put it




