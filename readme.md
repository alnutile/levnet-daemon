# LevNet

# ENV

~~~
MACHINE_ID=foobar
~~~

We will use that to identify the machines so if their IP changes we can still
aggregate that data at the api level.


This will be a daemon client for talking to the API
This client will run on Windows, Linux and Mac.

It will

  * do hourly speed tests and report to the API
  * if down it will track that info locally and upload to the API when it is back online


# Sending to API

This will have a service that looks through and sends a status every minute.

If, during that send there is any type of error it will just not mark the Result as sent

Then the hourly scheduler checks the database for items not marked sent and
tries to send them again.

# TODO

Transform Test Output then Save to DB

Scheduler hourly for items not marked sent

Set laravel log rotation

Send to my slack any errors
--app/SpeedTestService.php:116

Make a way to know what machine is checking in
--the api will get the IP but this might change

Setup testing then deployment
--have local machines pull latest code down

# Receiving API Notes

It will use the created_at, that is sent to it, to know when the test was taken
this will help on the send results that fail due to network issues
They can be sent at a later time via Scheduler

# Install

  * Ansible
  * pip install speedtest-cli
  * base php
  * make sure to setup Laravel Scheduler

~~~
* * * * php /var/foo/artisan schedule:run 1>> /dev/null 2>&1
~~~

Note too all the .env settings

