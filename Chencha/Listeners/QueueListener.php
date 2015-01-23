<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 05/01/15
 * Time: 00:22
 */

namespace Chencha\Listeners;

use Chencha\Dispatcher\QueuedRequestDispatcher;
use Cache;

class QueueListener
{
    use QueuedRequestDispatcher;

    public function fire($job, $eventData)
    {
        if (Cache::has($eventData['request'])) {
            $request = unserialize(Cache::get($eventData['request']));
            $this->runRequest($request);
        }
        Cache::forget($eventData['request']);
        $job->delete();
    }
}