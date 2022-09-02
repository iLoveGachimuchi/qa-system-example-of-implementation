<?

namespace System\Core;

class Request
{

    protected $requestUrl;
    protected $requestMethod;
    protected $get;
    protected $post;

    public function __construct()
    {
        $this->requestMethod = $this->getRequestMethod();
        $this->requestUrl = $this->getRequestUrl();
        $this->get = $this->requestVluesPrepare($_GET);
        $this->post = $this->requestVluesPrepare($_POST);
    }

    public function getBody($var = null)
    {
        return ($var == null ? $this->post : (isset($this->post[$var]) ? $this->post[$var] : null));
    }

    public function getQuery($var = null)
    {
        return ($var == null ? $this->get : (isset($this->get[$var]) ? $this->get[$var] : null));
    }

    public function isGet()
    {
        if ($this->requestMethod === 'GET') {
            return true;
        }
        return false;
    }

    public function isPost()
    {
        if ($this->requestMethod === 'POST') {
            return true;
        }
        return false;
    }

    public function dump()
    {
        return ['requestUrl' => $this->requestUrl, 'requestMethod' => $this->requestMethod, 'get' => $this->get, 'post' => $this->post];
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

    public function getDomainName()
    {
        return $_SERVER['HTTP_HOST'];
    }

    public function getDomainProtocol()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol;
    }

    /**
     * $param - @array (
     *      host: String,
     *      method: String,
     *      requestValue: Array,
     *      headers: Array(),
     *      returnResponce: Bool
     *  )
     */
    public function sendRequest($param, $curl = false)
    {
        $host = $param['host'];
        $method = $param['method'] ? $param['method'] : 'GET';
        $requestValue = $param['requestValue'] ? $param['requestValue'] : array();
        $headers = $param['headers'] ? $param['headers'] : array();
        $returnResponce = $param['returnResponce'] ? $param['returnResponce'] : true;

        $postvars = '';

        if (strtolower($method) == "post" && !$headers['Content-type']) {
            $headers['Content-type'] = 'application/x-www-form-urlencoded';
        }

        if (count($requestValue) > 0) {
            $sep = '';
            foreach ($requestValue as $key => $value) {
                $postvars .= $sep . urlencode($key) . '=' . urlencode($value);
                $sep = '&';
            }
        }

        if (!$curl) {
            if (count($headers) > 0) {
                $sep = $headersvars = '';
                foreach ($headers as $key => $value) {
                    $headersvars = $sep . $key . ': ' . $value;
                    $sep = '\r\n';
                }
            }
            $result = file_get_contents($host, false, stream_context_create(
                array(
                    'https' => array(
                        'method'  => $method,
                        'header'  => $headersvars,
                        'content' => http_build_query($requestValue)
                    ), 'ssl' => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                )
            ));
        } else {
            if (count($headers) > 0) {
                $headersvars = array();
                foreach ($headers as $key => $value) {
                    $headersvars[] = $key . ':' . $value;
                }
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $host);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headersvars);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
        }


        if ($returnResponce)
            return $result;
    }

    private function requestVluesPrepare($s)
    {
        if (!is_null($s)) {
            StringHelper::strTrim(StringHelper::htmlChars($s));
        }
        return $s;
    }
}
