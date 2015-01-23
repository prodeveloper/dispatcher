<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 05/01/15
 * Time: 00:15
 */

namespace Chencha\Dispatcher;
use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;
use App;
use Log;
trait QueuedRequestDispatcher
{
    use EventGenerator;
    use DispatchableTrait;

    function runRequest($request)
    {
        $this->raise($request);
        $this->dispatchEventsFor($this);

    }
    public function getDispatcher()
    {
        return App::make(QueueDispatcher::class);
    }
}