<?

namespace System\Core;

final class Router
{

    protected $routes = [];
    protected $requestUrl;
    protected $requestMethod;
    protected $requestHandler;
    protected $params = [];
    protected $placeholders = [
        ':seg' => '([^\/]+)',
        ':num' => '([0-9]+)',
        ':any' => '(.+)',
        ':rest' => '(\bget\b|\bpost\b|\bdelete\b|\bput\b)',
        ':token' => '([0-9]{4,30}[-]{1}[-0-9a-z]{4,32}[-]{1}[-0-9a-z]{3,20})'
    ];

    public function __construct()
    {
        $requestMethod = $this->getRequestMethod();
        $requestUrl = $this->getRequestUrl();
        $this->requestMethod = $requestMethod;
        $this->requestUrl = $requestUrl;
    }

    public function getRequestMethod()
    {
        if (isset($this->requestMethod)) {
            return $this->requestMethod;
        }
        return filter_var(getenv('REQUEST_METHOD'));
    }

    public function getRequestUrl()
    {
        if (isset($this->requestUrl)) {
            return $this->requestUrl;
        }
        $requestUrl = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        if (strpos($requestUrl, '?')) {
            $requestUrl = substr($requestUrl, 0, strpos($requestUrl, '?'));
        }
        return $requestUrl;
    }

    public function getRequestHandler()
    {
        return $this->requestHandler;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function add($route, $handler = null)
    {
        if ($handler !== null && !is_array($route)) {
            $route = array($route => $handler);
        }
        $this->routes = array_merge($this->routes, $route);
        return $this;
    }

    public function isFound()
    {
        $requestUrl = $this->getRequestUrl();
        if (isset($this->routes[$requestUrl])) {
            $this->requestHandler = $this->routes[$requestUrl];
            return true;
        }
        $find = array_keys($this->placeholders);
        $replace = array_values($this->placeholders);
        foreach ($this->routes as $route => $handler) {
            if (strpos($route, ':') !== false) {
                $route = str_replace($find, $replace, $route);
            }
            if (preg_match('#^' . $route . '$#', $requestUrl, $matches)) {
                $this->requestHandler = $handler;
                $this->params = array_slice($matches, 1);
                return true;
            }
        }

        return false;
    }

    public function executeHandler($handler = null, $params = null)
    {
        if ($handler === null) {
            throw new \InvalidArgumentException(
                'Request handler not setted out. Please check ' . __CLASS__ . '::isFound() first'
            );
        }

        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
        if (strpos($handler, '@')) {
            $restAction = explode('@', $handler);
            $restapiName = $restAction[0];
            $action = $restAction[1];
            if (class_exists($restapiName)) {
                $method = new $restapiName();
            } else {
                throw new \RuntimeException("Class '{$restapiName}' not found");
            }
            if (!method_exists($method, $action)) {
                throw new \RuntimeException("Method '{$restapiName}::{$action}' not found");
            }

            return call_user_func_array(array($method, $action), $params);
        }
    }
}
