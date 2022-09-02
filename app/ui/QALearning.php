<?

namespace UI;


use Traits\CurrentPage as CurrentPageTrait;
use Traits\Template as TemplateTrait;
use Traits\Construct as ConstructTrait;


class QALearning extends \System\Core\UIController
{

    use CurrentPageTrait;
    use TemplateTrait;
    use ConstructTrait;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public function Index()
    {
        $qacodelist = new \System\QA\QAKeyList();
        $qaanswerlist = new \System\QA\QAAnswerList();
        $keyscount = 0;
        $aliascount = 0;
        $answercount = count($qaanswerlist->__get());

        foreach ($qacodelist->__get() as $codekey => $alias) {
            $keyscount += 1;
            $aliascount += count($alias);
        }

        $this->templateVariables['totalkeyscount'] = $keyscount;
        $this->templateVariables['totalaliascount'] = $aliascount;
        $this->templateVariables['totalanswercount'] = $answercount;

        $this->templateFile = 'qa-learning-index.html';
        $this->templateVariables['title'] = 'Question-answering system';
        $this->templateVariables['description'] = 'Question-answering system';
        $this->renderHtmlPage();
    }

    public function questions()
    {

        $this->templateFile = 'qa-learning-list-question.html';
        $this->templateVariables['title'] = 'Question-answering system';
        $this->templateVariables['description'] = 'Question-answering system';
        $this->renderHtmlPage();
    }


    public function createQuestion()
    {
        if ($this->request->isPost()) {
            $this->createQuestionPost();

            $qacodelist = new \System\QA\QAKeyList();
            $this->templateVariables['questionAlias'] = $qacodelist->__get($this->request->getQuery('answer_code'));
        } else {
            $this->createQuestionGet();
        }


        $this->templateVariables['title'] = 'Question-answering system';
        $this->templateVariables['description'] = 'Question-answering system';
        $this->renderHtmlPage();
    }

    public function createAnswer()
    {
        if ($this->request->isPost())
            $this->createAnswerPost();

        if (!$this->templateFile)
            $this->templateFile = 'qa-learning-answer-add.html';

        $this->templateVariables['title'] = 'Question-answering system';
        $this->templateVariables['description'] = 'Question-answering system';
        $this->renderHtmlPage();
    }


    private function createQuestionPost()
    {
        $qacodelearm = new \System\QA\QALearn();

        if (!$this->request->getQuery('answer_code')) {
            $this->templateFile = 'qa-learning-question-add-selectcode.html';
            $this->templateVariables['pageError'] = 'Failed to add new alias';
        } else {


            if (!is_array($this->request->getBody('alias'))) {
                $this->templateVariables['pageError'] = 'Enter alias';
                $this->templateFile = 'qa-learning-question-add.html';
                return;
            }

            $aliases = array();
            foreach ($this->request->getBody('alias') as $value)
                if (strlen($value) != 0)
                    $aliases[] = strtolower($value);

            if ($qacodelearm->addNewAlias($this->request->getQuery('answer_code'), $aliases) === false)
                $this->templateVariables['pageError'] = 'Failed to add new alias';
            else
                $this->templateVariables['pageSuccess'] = 'Alias accepted';

            $this->templateFile = 'qa-learning-question-add.html';
        }
    }

    private function createQuestionGet()
    {
        $qacodelist = new \System\QA\QAKeyList();
        $answerCodes = $qacodelist->getCodes();

        if (!$this->request->getQuery('answer_code')) {
            $this->templateFile = 'qa-learning-question-add-selectcode.html';
            $this->templateVariables['answerCodes'] = $answerCodes;
        } else {
            if (!in_array($this->request->getQuery('answer_code'), $answerCodes)) {
                $this->responce->redirectTo('/ui/qa/learning/question/add');
                exit;
            }

            $this->templateFile = 'qa-learning-question-add.html';
            $this->templateVariables['answerCodes'] = array();
            $this->templateVariables['questionAlias'] = $qacodelist->__get($this->request->getQuery('answer_code'));
        }
    }

    private function createAnswerPost()
    {
        if (!$this->request->getBody('answer_code')) {
            $this->templateVariables['pageError'] = 'Enter answer code';
            return;
        }
        // $qacodelearm = new \System\QA\QALearn();
        $qacodelist = new \System\QA\QAKeyList();
        $qaanswerlist = new \System\QA\QAAnswerList();
        $answerCodes = $qacodelist->getCodes();

        if ($qaanswerlist->__get($this->request->getBody('answer_code'), $answerCodes)) {
            $this->templateVariables['pageError'] = 'Answer code already exist';
            return;
        }

        $this->templateVariables['answer_code'] = $this->request->getBody('answer_code');

        if ($this->request->getBody('alias')) {

            if (!is_array($this->request->getBody('alias'))) {
                $this->templateVariables['pageError'] = 'Enter alias';
                $this->templateFile = 'qa-learning-answer-add-aliases.html';
                return;
            }

            if (!$this->request->getBody('answertext')) {
                $this->templateVariables['pageError'] = 'Enter answer text';
                $this->templateFile = 'qa-learning-answer-add-aliases.html';
                return;
            }

            $this->templateVariables['answertext'] = $this->request->getBody('answertext');

            $answerText = $this->request->getBody('answertext');

            $aliases = array();

            foreach ($this->request->getBody('alias') as $value)
                if (strlen($value) != 0)
                    $aliases[] = strtolower($value);

            if (count($aliases) == 0) {
                $this->templateVariables['pageError'] = 'Alias not found';
                $this->templateFile = 'qa-learning-answer-add-aliases.html';
                return;
            }

            $qacodelearm = new \System\QA\QALearn();

            if ($qacodelearm->addNewAnswer($this->request->getBody('answer_code'), $aliases, $answerText) === false)
                $this->templateVariables['pageError'] = 'Failed to add new answer';
            else {
                $this->templateVariables['pageSuccess'] = 'Answer accepted';
                return;
            }
        }

        $this->templateFile = 'qa-learning-answer-add-aliases.html';
    }
}
