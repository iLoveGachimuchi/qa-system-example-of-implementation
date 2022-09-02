<?

namespace System\QA;

use \System\Lib\FuzzyWuzzy\Fuzz as Fuzz;
use \System\Lib\FuzzyWuzzy\Process as Process;

define('QA_CODEONLY', true);
define('QA_TEXT', false);

abstract class QASystem
{

    protected $minPersentMatching = 80;
    protected $qaKeyList = null;

    abstract public function answer($codeOnly = QA_TEXT);

    public function __construct()
    {
        $this->qaKeyList = new QAKeyList();
    }

    protected function extractQuestionKey($question)
    {
        $fuzz = new Fuzz();
        $extractResult = array('code' => '', 'percent' => 0.0);
        $matchResults = array();

        foreach ($this->qaKeyList->__get() as $code => $alias) {
            if (is_array($alias))
                for ($i = 0; $i < count($alias); $i++) {
                    $percent = $fuzz->ratio($question, $alias[$i]);

                    if ($percent > $extractResult['percent']) {
                        $extractResult['percent'] = $percent;
                        $extractResult['code'] = $code;
                    }
                }
            else {
                $percent = $fuzz->ratio($question, $alias);
                if ($percent > $extractResult['percent']) {
                    $extractResult['percent'] = $percent;
                    $extractResult['code'] = $code;
                }
            }
            if ($extractResult['percent'] > 0.0) {
                $addmatch = boolval(count($matchResults) == 0);
                for ($i = 0; $i < count($matchResults); $i++) {
                    if (in_array($extractResult['code'], $matchResults[$i])) {
                        if ($extractResult['percent'] > $matchResults[$i]['percent']) {
                            $matchResults[$i]['percent'] = $extractResult['percent'];
                        }
                    } else
                        $addmatch = true;
                }
                if ($addmatch)
                    $matchResults[] = $extractResult;
            }
        }

        if ($extractResult['percent'] >= $this->minPersentMatching)
            return array($extractResult['code']);

        if (count($matchResults) > 0) {

            $reporter = new \System\Reporter\Report('qa');
            $reporter->setdata(array(0 => array('question' => $question, 'matches' => $matchResults)));
            $reporter->commit();

            return $matchResults;
        }

        return null;
    }

    protected function keyExists($key)
    {
        return $this->qaKeyList->__get($key) != null;
    }

    protected function getAnwser($key)
    {
        $answerList = new QAAnswerList();

        $result = array();
        if (is_array($key)) {
            foreach ($key as $code) {
                if (is_array($answerList->__get($code)))
                    $result[] = $answerList->__get($code)[0];
                else
                    $result[] = $answerList->__get($code);
            }
        } else
            $result = is_array($answerList->__get($key) ? $answerList->__get($key) : array($answerList->__get($key)));

        return $result;
        // $this->qaKeyList-
    }
}
