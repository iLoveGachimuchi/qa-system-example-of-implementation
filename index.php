<?

if (!$_SERVER['HTTPS']) {
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $good_url = "https://" . $host . $request_uri;

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $good_url");
    exit;
}

define('AppDirectory', str_replace('\\', '/', __DIR__) . '/app');
require_once AppDirectory . '/autoload.php';

$result = array('ok' => 200, 'result' => array());

try {
    $config = require_once AppDirectory . '/config.php';
    $routes = require_once AppDirectory . '/routes.php';
    $app = new System\Core\App($config);
    $router = new System\Core\Router();

    $router->add($routes);
    if ($router->isFound())
        $result = $router->executeHandler($router->getRequestHandler(), $router->getParams());
    else
        $result = array('ok' => 404, 'result' => array('error' => 'Page not found'));
} catch (Exception $e) {
    $result = array('ok' => ($e->getCode() ? $e->getCode() : http_response_code()), 'result' => array('error' => $e->getMessage()));
}

$responce = new System\Core\Responce();

$responce->setHeader('json')->withJson($result);
