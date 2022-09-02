<?

namespace System\QA;

use \System\Core\Cache as Cache;

class QALearn
{
    public function addNewAlias($questionCode, $alias)
    {
        $qaKeyList = new QAKeyList();
        if (!in_array($questionCode, $qaKeyList->getCodes()) or !$alias)
            return false;

        if (is_array($alias)) {
            $qaKeyList->remove($questionCode);
            foreach ($alias as $al)
                $qaKeyList->add($questionCode, $al);

            $qaKeyList->commit(true);
        } else
            return $qaKeyList->add($questionCode, $alias)->commit();
    }

    public function addNewAnswer($questionCode, $alias, $answer)
    {
        $qaKeyList = new QAKeyList();
        $qaAnswerList = new QAAnswerList();

        if ($qaAnswerList->__get($questionCode) or !$alias)
            return false;

        foreach ($alias as $al)
            $qaKeyList->add($questionCode, $al);

        if (!$qaKeyList->commit())
            return false;

        return $qaAnswerList->add($questionCode, $answer)->commit();
    }

    public function getCodes()
    {
        $codes = array();

        foreach ($this->keys as $code => $alias)
            $codes[] = $code;

        return $codes;
    }
}
