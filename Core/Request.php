<?php

namespace Simple\Core;

class Request
{
    private $path;
    private $requestMethod;
    private array $urlSegmant;
    private array $body = [];

    /**
     * construct the object
     */
    public function __construct($path = '')
    {
        if (empty($path)) {
            $this->path = trim($_SERVER['REQUEST_URI'], '/');
        } else {
            $this->path = $path;
        }

        $this->requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $this->parsePath();
    }

    /**
     * func parsePath: take the path
     * @param string $path: the path to be parsed
     * @return void
     */
    private function parsePath()
    {
        $path = $this->path;

        $pos = strpos($path, '?');
        if ($pos !== false) {
            $path = substr($path, 0, $pos);
        }

        $path = trim($path, '/');
        if (empty($path)) $path = '/';

        $this->urlSegmant = explode('/', $path);

        $this->path = $path;
    }

    public function getSegment(int $index)
    {
        $segments = count($this->urlSegmant);
        if($segments = 0 || $index > $segments){
            return false;
        } 

        return $this->urlSegmant[$index];
    }

    /**
     * return the parameters in the request method
     * @param void
     */
    public function getRequestBody()
    {
        if ($this->requestMethod === 'get') {
            foreach ($_GET as $key => $value) {
                $this->body[$this->requestMethod][$key] = filter_input(
                    INPUT_GET,
                    $key,
                    FILTER_SANITIZE_SPECIAL_CHARS
                );
            }
        }

        if ($this->requestMethod === 'post') {
            foreach ($_POST as $key => $value) {
                $this->body[$this->requestMethod][$key] = filter_input(
                    INPUT_POST,
                    $key,
                    FILTER_SANITIZE_SPECIAL_CHARS
                );
            }
        }

        return $this->body;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getRequestMethod()
    {
        return strtolower($this->requestMethod);
    }
}
