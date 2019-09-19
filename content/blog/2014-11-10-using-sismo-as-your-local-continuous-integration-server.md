---
id: 2014-11-10-using-sismo-as-your-local-continuous-integration-server
title: Using Sismo as Your Personal CI Server on Windows
summary: Sismo is a lightweight and easy to install continuous testing server which you can use locally and trigger from a git post-commit hook.
date: 2014-11-10
tags:
    - continuous integration
    - sismo
---

There are some really good continuous integration servers out there which you ~~can~~ should use. Well known are Travis CI and Jenkins. I had a look at those but they seemed to be a hassle to setup. I did install and try [PHPCI](https://www.phptesting.org/). It looks promising but I couldn't get it to work properly because of some error messages and bugs. After trying PHPCI I changed my mind: A CI server is way too much and heavy on resources for what I really need... Something that checks my committed code and runs several tests in the background, and once it's done report the result. This way I know within seconds if I didn't break anything. After some more research I found [Sismo](http://sismo.sensiolabs.org/), a Continuous Testing Server written in PHP. Great, lightweight (only a single 398KB file) and even written in PHP!

## Configuration

The installation guide on their site is pretty easy to understand. However for windows it didn't work straight away and I had to find out by trail and error how to get it working.

You must set system environment variables in the advanced system settings, which you can find in "Control Panel\System and Security\System" -> Advanced Settings -> Environment Variables. Once set, you need to restart your pc for these to work.

```
SISMO_DATA_PATH "/path/to/sismo/data"
SISMO_CONFIG_PATH "/path/to/sismo/config.php"
```

The config.php file. This is where you manage projects. As you can see I've added a custom notifier (more on that below) and set a default command that checks for composer.json. If it's needed it runs composer and triggers makefile afterwards. In the Makefile I've set project specific tests.

```php
<?php

// Create a custom notifier using toast
require('ToastNotifier.php');
$notifiers = array(
    new Sismo\Contrib\ToastNotifier()
);

// Set default command: Run composer and phing if there is a composer file
Sismo\Project::setDefaultCommand('if [ -f composer.json ]; then composer install --dev --prefer-source && make test; fi');

// Initialize projects
$projects = array();

// Add projects with custom settings
$projects['project-slug'] = new Sismo\Project('My Project', 'path\to\repository', $notifiers, 'project-slug');
$projects['project-slug']->setBranch('develop');

return $projects;
```

## Trigger Sismo after commits

The post-commit hook also gave me some headaches. I only got errors or sismo wasn't triggered at all. After tinkering with the command I finally got it to work. It runs after a git commit in the background.

```bash
#!/bin/sh

HASH=`git rev-parse HEAD`
SLUG=<project-slug>

php path/to/sismo.php build --force --quiet $SLUG $HASH 2>&1 &
```

## Notifications

Ofcourse you want notifications with the test results. Growl for Windows is supported by default in Sismo. However I couldn't get it working. I think I needed to register Sismo with growl but I decided I didn't want to use an extra service anyway. I'm using nodejs a lot and it has a nice notification system (``node-notifier``). It integrates with the windoews 10 notification center and enables you to send notifications from the console whenever needed. So it's perfect to integrate it into a custom notification class.

```php
<?php

namespace Sismo\Contrib;

use Sismo\Commit;
use Sismo\Notifier\Notifier;

/**
 * Notifies build status via toast notifications
 *
 * Toast binaries need to be available in your PATH and can be downloaded at
 * https://github.com/nels-o/toaster
 *
 * @author Geert Eltink <https://xtreamwayz.github.io>
 */
class ToastNotifier extends Notifier
{
    private $application;
    private $notifications;
    private $format;

    public function __construct($application = 'sismo', $format = "%message% by %author%")
    {
        $this->application   = $application;
        $this->format        = $format;
        $this->notifications = array(
            array('status' => 'Success', 'enabled' => true),
            array('status' => 'Fail', 'enabled' => true),
        );
    }

    public function notify(Commit $commit)
    {
        return $this->doNotify($commit->isSuccessful() ? true : false, $commit->getProject()->getName(), $this->format($this->format, $commit));
    }

    private function doNotify($status, $title, $message)
    {
        $dir = dirname(__FILE__);
        $command = sprintf('%s -t "%s" -m "%s" -p "%s/%s"',
            'path/to/nodejs/node_modules/node-notifier/vendor/toaster/toast.exe',
            $title,
            $message,
            $dir,
            $status ? 'icon-success.png' : 'icon-fail.png'
        );

        exec($command, $output, $return_var);
    }
}
```

## View results

With PHP 5.4+ you can use the Sismo build-in web server to view the build results:

```php
php sismo.php run localhost:9000
```
