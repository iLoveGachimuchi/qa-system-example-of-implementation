<?

namespace System\QA;

class QASimple extends QASystem
{
    protected $qaKey = array();

    public function question($query)
    {
        $this->qaKey = $this->extractQuestionKey($query);
    }

    public function setQuestion($code)
    {
        if ($this->keyExists($code))
            $this->qaKey[] = $code;
    }

    public function answer($onlyCode = QA_TEXT)
    {
        return (count($this->qaKey) == 0 ? array() : ($onlyCode ? $this->qaKey : $this->getAnwser($this->qaKey)));
    }
}
