---
id: 2016-02-07-zend-expressive-console-cli-commands
title: zend-expressive console cli commands
summary: Use Symfony console for your zend-expressive console commands.
draft: false
public: true
published: 2016-02-07T11:40:00+01:00
modified: 2016-02-16T12:25:00+01:00
tags:
    - zend-expressive
    - console
    - cli
---

zend-expressive does not come out of the box with a console for handling cli commands. However it's easy to add this
and make full use of the container and its dependencies.

## Install Symfony Console

First the Symfony console needs to be installed.

```bash
$ composer require symfony/console
```

## Creating The Console

Next will be the console bootstrap file. It loads everything that's needed and inject the commands from the
configuration.

```php
<?php // console.php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

/** @var \Interop\Container\ContainerInterface $container */
$container = require __DIR__ . '/config/container.php';
$application = new Application('Application console');

$commands = $container->get('config')['console']['commands'];
foreach ($commands as $command) {
    $application->add($container->get($command));
}

$application->run();
```

The console needs to know which commands to setup. Here the ``console -> commands`` key is chosen, however you can
change this as you like.

```php
<?php // config/autoload/console.global.php

return [
    'dependencies' => [
        'invokables' => [
        ],

        'factories' => [
        ],
    ],

    'console' => [
        'commands' => [
        ],
    ],
];
```

And that's about it. You've got a working console.

## Example greet command

But what is a console without a command. Let's re-create the example
[greet command](http://symfony.com/doc/current/cookbook/console/console_command.html) from the Symfony cookbook.
First up is the command itself. To get a better idea of what you really can do, a logger is injected to log the
executed command.

```php
<?php // src/App/Command/GreetCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Logger;

class GreetCommand extends Command
{
    private $logger;

    /**
     * Constructor
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;

        parent::__construct();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('demo:greet')
            ->setDescription('Greet someone')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            );
    }

    /**
     * Executes the current command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello ' . $name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
        
        $this->logger->info('GreetCommand triggered', ['name' => $name]);
    }
}
```

Most likely dependencies are needed, which are injected with a factory. In this example an instance of Monolog is 
injected.

```php
<?php // src/App/Command/GreetCommandFactory.php

namespace App\Command;

use Interop\Container\ContainerInterface;
use Monolog\Logger;

class GreetCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new GreetCommand(
            $container->get(Logger::class)
        );
    }
}
```

Add this command and its factory to the configuration so it can actually be used.

```php
<?php // config/autoload/console.global.php

return [
    'dependencies' => [
        'invokables' => [
        ],

        'factories' => [
            App\Command\GreetCommand::class => App\Command\GreetCommandFactory::class,
        ],
    ],

    'console' => [
        'commands' => [
            App\Command\GreetCommand::class,
        ],
    ],
];
```

And now it's ready to be used:

```bash
$ php console.php demo:greet world
$ php console.php demo:greet world --yell
```
