<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 04/01/15
 * Time: 23:53
 */

namespace Chencha\Dispatcher;


use Laracasts\Commander\Events\EventDispatcher;

class QueueDispatcher extends EventDispatcher
{
    /**
     * We'll make the fired event name look
     * just a bit more object-oriented.
     *
     * @param $event
     * @return mixed
     */
    function getEventName($event)
    {
        return "queued." . parent::getEventName($event);

    }
}