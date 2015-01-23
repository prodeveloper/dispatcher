<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 04/01/15
 * Time: 23:33
 */

namespace Chencha\Dispatcher;

use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;
use Queue;
use Chencha\Listeners\QueueListener;
use Cache;
use Exception;

trait RequestDispatcher
{
    use EventGenerator;
    use DispatchableTrait;

    function runRequest($request)
    {
        $queued_request=clone $request;
        $this->raise($request);
        $this->dispatchEventsFor($this);

        try {
            $key = uniqid();
            $s_request = serialize($queued_request);
            Cache::put($key, $s_request, 30);
            Queue::push(QueueListener::class, ['request' => $key]);
        } catch (Exception $e) {
            \Log::debug($e);
        }

        //Dispatch request to possible queued events listeners

    }
}