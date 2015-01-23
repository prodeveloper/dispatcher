<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 16/11/14
 * Time: 14:21
 */

namespace Chencha\Dispatcher;

use Chencha\Lib\DtoPath;
use Illuminate\Events\Dispatcher;

/**
 * Class RequestHandlers
 * @package Jobkikr\Helpers
 *
 * Provides base functionality to register listeners
 */
abstract class EventSubscriber
{
    protected $listeners;
    protected $path;

    function __construct($path = null)
    {
        if (is_null($path)) {
            $path = (new DtoPath())->guess($this);
        }
        $this->path = $path;
    }

    /**
     * @param Dispatcher $events
     */
    function subscribe($events)
    {
        foreach ($this->beforeListeners() as $listener) {
            $events->listen($this->path . '.*', $listener, 10);
        }

        foreach ($this->duringListeners() as $listener) {
            $events->listen($this->path . '.*', $listener, 5);
        }
        foreach ($this->afterListeners() as $listener) {
            $events->listen($this->path . '.*', $listener, 0);
        }
        foreach ($this->queuedListeners() as $listener) {
            $events->listen("queued." . $this->path . '.*', $listener, 0);
        }

    }


    /**
     * @return array
     */
    function beforeListeners()
    {
        return [];
    }

    function afterListeners()
    {
        return [];
    }

    function queuedListeners()
    {
        return [];
    }

    /**
     * @return array
     */
    abstract function duringListeners();



} 