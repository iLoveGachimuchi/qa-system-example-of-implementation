<?

namespace System\Core;

final class App
{

    public static $config;

    public function __construct($config)
    {
        session_start();
        $this->config = $config;
    }

    public function getConfig($variable)
    {
        return $this->config[$variable];
    }
}
