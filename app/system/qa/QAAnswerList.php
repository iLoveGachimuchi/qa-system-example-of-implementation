<?

namespace System\QA;

use \System\Core\Cache as Cache;

class QAAnswerList
{
    protected $answers = array();

    public function __construct()
    {
        $cache = new Cache(CacheDirectory, time(), '.json');
        if (!$cache->isCached('qaanswerlist'))
            $cache->setCache('qaanswerlist', array());

        $this->answers = $cache->getCache('qaanswerlist');
    }

    public function __get($name = null)
    {
        return ($name == null ? $this->answers : $this->answers[$name]);
    }

    public function add($code, $text)
    {
        $this->update = true;
        $this->answers[$code][] = $text;

        return $this;
    }

    public function commit()
    {
        if (!$this->update)
            return false;

        $cache = new Cache(CacheDirectory, time(), '.json');
        $cache->setCache('qaanswerlist', $this->answers);

        $this->update = false;

        return true;
    }
}
