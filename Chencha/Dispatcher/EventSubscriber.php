<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 16/11/14
 * Time: 14:21
 */

namespace Chencha\Dispatcher;

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
            $path = $this->guessPath();
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

    function guessPath()
    {
        $class = get_class($this);
        if (strpos($class, "Command")) {
            $path = preg_replace("#([\s\S]*?)\\\(Handlers\\\CommandHandler?s)#", "$1\\Commands", $class);
        } elseif (strpos($class, "Request")) {
            $path = preg_replace("#([\s\S]*?)\\\(Handlers\\\RequestHandler?s)#", "$1\\Requests", $class);
        } elseif (strpos($class, "Event")) {
            $path = preg_replace("#([\s\S]*?)\\\(Handlers\\\EventHandler?s)#", "$1\\Events", $class);
        } else {
            throw new \Exception("Path not parsed");
        }
        return str_replace("\\", ".", $path);
    }

    function pathToRequest($package)
    {
        $cls = get_class($this);

        return "Ihub.{$package}.Requests";
    }

    function pathToEvents($package)
    {
        return "Ihub.{$package}.Events";
    }

    function pathToCommands($package)
    {
        return "Ihub.{$package}.Commands";
    }


} 