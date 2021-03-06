<?php

namespace Simple\Core;

class Request implements IRequest
{
    private $path;
    private $requestMethod;
    private $requestType;
    private array $urlSegmants = [];
    private array $body = [];

    /**
     * construct the object
     */
    public function __construct($path = null)
    {
        $this->requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $this->parsePath($path);
        $this->requestType = $this->parseRequestType();
    }

    /**
     * func parsePath: take the path
     * @param string $path: the path to be parsed
     * @return void
     */
    private function parsePath(string $path = null)
    {
        $path = $path ?? trim($_SERVER['QUERY_STRING'], '/');

        $pos = strpos($path, '?');
        if ($pos !== false) {
            $path = substr($path, 0, $pos);
        }

        $path = trim($path, '/');
        if (empty($path)) $path = '/';

        $this->urlSegmants = explode('/', $path);

        $this->path = $path;
    }

    private function parseRequestType()
    {
        $host = $_SERVER['HTTP_HOST'];
        $prefex = explode('.', $host)[0];
        return $prefex === 'api' ? 'api' : 'web';
    }

    public function getRequestType()
    {
        return $this->requestType;
    }

    public function getSegment(int $index)
    {
        $segments = count($this->urlSegmants);
        if ($segments == 0 || $index > $segments) {
            return false;
        }

        return $this->urlSegmants[$index];
    }

    public function getSegments()
    {
        return $this->urlSegmants;
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
        } elseif ($this->requestMethod === 'post') {
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

    public function getAjaxData()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data);
        return $data;
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
