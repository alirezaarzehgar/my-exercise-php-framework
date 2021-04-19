<?php

namespace app\core;

use tidy;

class Request
{
    public function uri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $position = strpos($uri, '?');

        return !$position ? $uri : substr($uri, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'post';
    }

    public function body()
    {
        $body = [];

        if ($this->isGet())
            foreach ($_GET as $key => $value)
                $body[$key] = $value;

        if ($this->isPost())
            foreach ($_POST as $key => $value)
                $body[$key] = $value;

        return $body;
    }
}
