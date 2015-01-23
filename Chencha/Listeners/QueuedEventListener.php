<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 04/01/15
 * Time: 23:45
 */

namespace Chencha\Listeners;

use Laracasts\Commander\Events\EventListener;
use ReflectionClass;
use Log;

class QueuedEventListener extends EventListener
{
    /**
     * Figure out what the name of the class is.
     *
     * @param $event
     * @return string
     */
    protected function getEventName($event)
    {
        $original_event = (new ReflectionClass($event))->getShortName();
        return str_replace("queued.", '', $original_event);
    }


}