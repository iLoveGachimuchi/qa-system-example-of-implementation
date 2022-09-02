<?

namespace System\Core;

use System\Core\Request as Request;
use System\Core\Responce as Responce;
use System\Core\Validator as Validator;
use System\Core\Session as Session;
use System\Core\Cookie as Cookie;
use Exception;

abstract class Methods
{

    protected $validator;
    protected $request;
    protected $responce;
    protected $session;
    protected $cache;
    protected $cookie;

    public function __construct()
    {
        $this->request = new Request;
        $this->validator = new Validator;
        $this->responce = new Responce;
        $this->session = new Session;
        $this->cookie = new Cookie;
    }

    public function restMethodCheck($restMethod, $methods)
    {
        if ($restMethod === null or !in_array(strtolower($restMethod), $methods))
            throw new Exception('Method not accepted', 406);

        return true;
    }

    public function restAPICheck($restMethod, $methods, $key)
    {
        $this->restMethodCheck($restMethod, $methods);

        $authtoken = new \Model\AuthToken();

        if (!$authtoken or $authtoken === null or !$authtoken->haveAccess($key))
            throw new Exception('Access denied', 403);


        return true;
    }
}
