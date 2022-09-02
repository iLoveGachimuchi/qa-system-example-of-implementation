<?

namespace System\QA;

class QAChat extends QASystem
{
    protected $qaKey = '';

    public function question($query)
    {
        $this->qaKey = $this->extractQuestionKey($query);
    }
    public function answer($onlyCode = QA_TEXT)
    {
        if ($this->qaKey === null)
            return null;

        return ($onlyCode ? $this->qaKey : $this->getAnwser($this->qaKey));
    }
}
