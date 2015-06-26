# CommandPlanner

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/abc4ec8f-3827-42e6-8eea-81a995aa0b1b/mini.png)](https://insight.sensiolabs.com/projects/abc4ec8f-3827-42e6-8eea-81a995aa0b1b)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Babacooll/CommandPlanner/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Babacooll/CommandPlanner/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Babacooll/CommandPlanner/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Babacooll/CommandPlanner/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Babacooll/CommandPlanner/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Babacooll/CommandPlanner/build-status/master)

CommandPlanner is a cron management for Symfony Console component commands.

## Installation

CommandPlanner is available through composer :

```Shell
$ php composer.phar require babacooll/command-planner ~0.0.1
```

## Usage

You can load method in CommandPlanner by two ways :

## Example inline

PHP File :

```php
<?php

require('vendor/autoload.php');

use CommandPlanner\CommandPlanner;

$commandPlanner = new CommandPlanner();

$commandPlanner->add(
    new \CommandPlanner\Wrapper\CommandWrapper(
        'CommandPlanner\Tests\Data\TestCommand',
        'Symfony\Component\Console\Application',
        \Cron\CronExpression::factory('* * * * *'),
        [
            'parameters' => ['test'],
            'log_file'   => 'test.log',
            'options'    => []
        ]
    )
);

$commandPlanner->run();
```

## Example from config

PHP File :

```php
<?php

require('vendor/autoload.php');

use CommandPlanner\CommandPlanner;

$commandPlanner = new CommandPlanner();

$commandPlanner->addCommandsFromConfig('config/config.yml');

$commandPlanner->run();
```

Config File :

```yml
command_planner:
    commands:
        my_first_command:
            namespace : CommandPlanner\Tests\Data\TestCommand
            timing: '* * * * * *'
            parameters: ['test']
            options: ['--yell']
            log_file: test.log

    application: Symfony\Component\Console\Application

```

These examples use CommandPlanner\Tests\Data\TestCommand test command from this package.

## TODO

* Unit testing
* Exception handler
