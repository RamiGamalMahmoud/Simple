<?php

namespace Simple\Core;

use Simple\Contracts\RequestInterface;

class Request implements RequestInterface
{
    /**
     * Requested path
     * 
     * @var string|null $path
     */
    private $path;

    /**
     * @var string $requestMethod
     */
    private $requestMethod;

    /**
     * The request type 
     * 
     * @var string $requestType
     */
    private $requestType;

    /**
     * @var array $urlSegmants
     */
    private array $urlSegmants = [];

    /**
     * @var array $requestBody
     */
    private array $requestBody = [];

    /**
     * @var bool $isRefreshed
     */
    private bool $isRefreshed;

    public function __construct($path = null)
    {
        $this->isRefreshed = isset(
            $_SERVER['HTTP_CACHE_CONTROL']
        ) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

        $this->requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $this->parsePath($path);
        $this->requestType = $this->parseRequestType();
    }

    /**
     * Parses the requested path
     * 
     * @param string|null $path: the path to be parsed
     * @return void
     */
    private function parsePath(string $path = null)
    {
        $path = $path ?? trim($_SERVER['REQUEST_URI'], '/');
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

    public function input(string $key)
    {
        $inputs = $this->getRequestBody()[$this->getRequestMethod()];
        if (isset($inputs[$key])) {
            return $inputs[$key];
        }
        return null;
    }
    /**
     * return the parameters in the request method
     * @param void
     */
    public function getRequestBody()
    {
        if ($this->requestMethod === 'get') {
            foreach ($_GET as $key => $value) {
                $this->requestBody[$this->requestMethod][$key] = filter_input(
                    INPUT_GET,
                    $key,
                    FILTER_SANITIZE_SPECIAL_CHARS
                );
            }
        } elseif ($this->requestMethod === 'post') {
            foreach ($_POST as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $this->requestBody[$this->requestMethod][$key][$k] = filter_var(
                            $v,
                            FILTER_SANITIZE_SPECIAL_CHARS
                        );
                    }
                } else {
                    $this->requestBody[$this->requestMethod][$key] = filter_input(
                        INPUT_POST,
                        $key,
                        FILTER_SANITIZE_SPECIAL_CHARS
                    );
                }
            }
        }

        return $this->requestBody;
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
        return $this->requestMethod;
    }
}
