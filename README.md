[![SensioLabsInsight](https://insight.sensiolabs.com/projects/abc4ec8f-3827-42e6-8eea-81a995aa0b1b/mini.png)](https://insight.sensiolabs.com/projects/abc4ec8f-3827-42e6-8eea-81a995aa0b1b)

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
