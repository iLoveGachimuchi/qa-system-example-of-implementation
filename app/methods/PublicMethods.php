<?

namespace Methods;

use System\QA\QASimple as QASimple;

class PublicMethods extends \System\Core\Methods
{

    public function __construct()
    {
        parent::__construct();
    }

    public function test()
    {
        return $this->responce->result(array());
    }

    public function info()
    {
        $this->restMethodCheck($this->request->getRequestMethod(), array('get'));

        return $this->responce->result($GLOBALS['app']->getConfig('info'));
    }

    public function question()
    {
        $this->restMethodCheck($this->request->getRequestMethod(), array('get'));

        $qa = new QASimple();
        $qa->question($this->request->getQuery('query'));

        return $this->responce->result($qa->answer(QA_CODEONLY));
    }

    public function answer()
    {
        $this->restMethodCheck($this->request->getRequestMethod(), array('get'));

        $qa = new QASimple();
        $qa->setQuestion(($this->request->getQuery('query')));

        return $this->responce->result($qa->answer(QA_TEXT));
    }
}
