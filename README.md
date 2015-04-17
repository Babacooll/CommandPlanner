# CommandPlanner

CommandPlanner is a cron management for Symfony Console component commands.

## Example

PHP File :

```php
<?php

require('vendor/autoload.php');

use CommandPlanner\CommandPlanner;

$commandPlanner = new CommandPlanner('config', 'config.yml');

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

This example uses CommandPlanner\Tests\Data\TestCommand test command from this package.

## TODO

* Unit testing
* Exception handler
