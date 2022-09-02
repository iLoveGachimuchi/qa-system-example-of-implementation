<?

namespace System\Core;

use System\Core\Request as Request;
use System\Core\Responce as Responce;
use System\Core\Validator as Validator;
use System\Core\Session as Session;
use System\Core\Cookie as Cookie;

abstract class UIController
{
    protected $validator;
    protected $request;
    protected $responce;
    protected $session;
    protected $cache;
    protected $cookie;
    protected $template;

    public function __construct()
    {
        $this->request = new Request;
        $this->validator = new Validator;
        $this->responce = new Responce;
        $this->session = new Session;
        $this->cookie = new Cookie;

        $this->config = $GLOBALS['app']->config;
        $this->initTemplate();
        $this->cache = new Cache($this->config['cache']['path'], time(), '.cache');
        $this->site = $this->site = $this->config['site'];
    }

    public function initTemplate()
    {
        $config = $this->config['template'];
        $this->template = new Template($config['directory']);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
        return true;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
