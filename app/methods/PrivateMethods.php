<?

namespace Methods;

use System\QA\QASimple as QASimple;
use System\QA\QAChat as QAChat;

use Exception;

class PrivateMethods extends \System\Core\Methods
{

    public function __construct()
    {
        parent::__construct();
    }

    public function test()
    {
        return $this->responce->result(array());
    }

    public function info($token = null)
    {
        $this->restAPICheck($this->request->getRequestMethod(), array('get'), $token);

        return $this->responce->result($GLOBALS['app']->getConfig('info'));
    }

    public function question($token = null)
    {
        $this->hasRequestAccess($token);

        $qa = new QASimple();
        $qa->question($this->request->getQuery('query'));

        return $this->responce->result($qa->answer(QA_CODEONLY));
    }


    public function chat($token = null)
    {
        $this->hasRequestAccess($token);

        $qa = new QAChat();

        return $this->responce->result($this->request->getQuery('query'));
    }


    private function hasRequestAccess($token = null)
    {
        $this->restAPICheck($this->request->getRequestMethod(), array('get'), $token);

        if (!$this->request->getQuery('query'))
            throw new Exception('No query', 404);

        return;
    }
}
