# CommandPlanner

CommandPlanner is a cron management for Symfony Console component commands.

## Example

```php
<?php

require('vendor/autoload.php');

use Symfony\Component\Console\Application;
use CommandPlanner\CommandPlanner;
use CommandPlanner\Tests\Data\TestCommand;

$commandPlanner = new CommandPlanner(new Application());

$commandPlanner
    ->add(new TestCommand(), '* * * * * *', ['lol'])
    ->add(new TestCommand(), '* * * * * *', ['lal', '--yell'])
    ->run();
```

## TODO

* Unit testing
* Log management
* Exception handler
