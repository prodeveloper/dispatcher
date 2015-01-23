<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 23/01/15
 * Time: 23:34
 */

namespace Chencha\Lib;


class DtoPath
{
    function guess($handler)
    {
        $class = get_class($handler);
        if (strpos($class, "Command")) {
            $path = $this->_isACommand($class);
        } elseif (strpos($class, "Request")) {
            $path = $this->isARequest($class);
        } elseif (strpos($class, "Event")) {
            $path = $this->isAnEvent($class);
        } else {
            $this->_pathNotFound();
        }

        $this->_checkParseFailed($path, $class);
        return str_replace("\\", ".", $path);
    }

    /**
     * @param $class
     * @return mixed
     */
    protected function _isACommand($class)
    {
        $path = preg_replace("#([\s\S]*?)\\\(Handlers\\\CommandHandlers?)#", "$1\\Commands", $class);
        return $path;
    }

    /**
     * @param $class
     * @return mixed
     */
    protected function isARequest($class)
    {
        $path = preg_replace("#([\s\S]*?)\\\(Handlers\\\RequestHandlers?)#", "$1\\Requests", $class);
        return $path;
    }

    /**
     * @param $class
     * @return mixed
     */
    protected function isAnEvent($class)
    {
        $path = preg_replace("#([\s\S]*?)\\\(Handlers\\\EventHandlers?)#", "$1\\Events", $class);
        return $path;
    }

    protected function _pathNotFound()
    {
        throw new \Exception("Path not parsed");
    }

    /**
     * @param $path
     * @param $class
     * @throws \Exception
     */
    protected function _checkParseFailed($path, $class)
    {
        if ($path == $class) {
            $this->_pathNotFound();
        }
    }
}