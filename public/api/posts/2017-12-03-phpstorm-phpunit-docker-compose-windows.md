---
id: 2017-12-03-phpstorm-phpunit-docker-compose-windows
title: Running PHPUnit in PhpStorm with docker-compose on Windows
summary: Setup PhpStorm with docker-compose and PHPUnit integration on Windows.
draft: false
public: true
published: 2017-12-03T11:45:00+00:00
modified: 2017-12-03T12:45:00+01:00
tags:
    - PhpStorm
    - PHPUnit
    - docker-compose
    - windows
---

I've followed some guides on how to setup PhpStorm with docker-compose but I never got it to work. I decided to start from scratch and forget about all the guides.

## Setup docker

To connect to docker from PhpStorm on windows the only way I managed to do that is by exposing the daemon without TLS. You can find it on the general settings screen.

## Configure Docker in PhpStorm

Next Docker is configured in PhpStorm. This is done at `File -> Settings -> Build, Execution, Deployment -> Docker`. Click the green + and add these details:

- Name: `Docker`
- Connect to Docker daemon with: `TCP socket`
- Engine API URL: `tcp://localhost:2375`
- Path mappings: `/c/Users <-> C:\Users`.

Click apply.

## Configure PHP

PHP is configured at `File -> Settings -> Languages & Frameworks -> PHP`.

The PHP language level should be set to the minimum required version for your project. I used 7.1 up to 2 days ago. Once I upgrade the servers I'll move forward to 7.2.

The CLI Interpreter is were docker-compose comes into play. Click the 3 dots on the right side. This will open up the CLI Interpreters dialog.

On that screen click the green + and select `From Docker, Vagrant, VM, Remote...`.

- Name: `Docker PHP`
- Remote: `Docker Compose` (selector)
- Server: `Docker` *(It's the server configured in the previous step)*
- Configuration file(s): `./docker-compose.yml`
- Service: `php` *(it's the php service name from docker-compose.yml)*
- Environment variables: *(leave empty, but click the 3 dots on the right and deselect `Include parent environment variables`)*
- PHP interpreter path: `php`

Clicking apply should detect the PHP version, its configuration file and the debugger version if it's enabled.

Leave the `Visible only for this project` checked. Making it available is not so useful as it will reuse the docker-compose.yml file from the current project. I would expect to automatically use `./docker-compose.yml` in other project, but it doesn't. Also deselecting `Include parent environment variables` was a well hidden feature that caused me problems. Leaving it enabled gave errors and failed to detect PHP. Somewhere a wrong environment variable is injected, but I haven't figured out what process is responsible for it.

## PHPUnit

Next step is PHPUnit. It is configured at `File -> Settings -> Languages & Frameworks -> PHP -> Test Frameworks`.

Click the green + and select `PHPUnit by Remote Interpreter`. And as the interpreter select `Docker PHP` from the previous step. Most settings are auto configured for you.

- CLI Interpreter: `Docker PHP`
- Path Mappings: `<Project root> -> /app`
- PHPUnit library: `Use Composer autoloader`
- Path to script: `/app/vendor/autoload.php`
- Default configuration file: `/app/phpunit.xml.dist` *(Enable via the checkbox and add the path)*

Clicking apply should detect the PHPUnit version.

## PHPUnit Run/Debug Configuration

Adding a run/debug configuration makes your life easy and enables one-click running of running your tests.

Open the Run/Debug Configuration at `Run -> Edit Configurations`.

Green + again and select PHPUnit.

- Name: `PHPUnit`
- Test Runner: `Defined in the configuration file`

Click apply.

Now if you select your PHPUnit run/debug configuration on the top right and click that green run button you tests are started. If you click the `Run with coverage` button you can see the code coverage metrics if you enable them with `Tools -> Show Code Coverage Data`.

Enjoy. I know I will now.
