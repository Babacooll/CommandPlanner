<?php

namespace CommandPlanner\Listener;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class NullListener
 *
 * @package CommandPlanner\Listener
 */
class NullListener
{
    /**
     * @param Event $event
     */
    public function onExceptionListener(Event $event)
    {
    }
}
