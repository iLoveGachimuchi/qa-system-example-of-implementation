<?

namespace System\QA;

use \System\Core\Cache as Cache;

class QAKeyList
{
    protected $keys = array();

    public function __construct()
    {
        $cache = new Cache(CacheDirectory, time(), '.json');
        if (!$cache->isCached('qakeylist'))
            $cache->setCache('qakeylist', array('signup_help' => array('register', 'signup', 'i cant register', 'i cant signup')));

        $this->keys = $cache->getCache('qakeylist');
    }

    public function __get($name = null)
    {
        return ($name !== null ? $this->keys[$name] : $this->keys);
    }

    public function getCodes()
    {
        $codes = array();

        foreach ($this->keys as $code => $alias)
            $codes[] = $code;

        return $codes;
    }

    public function remove($code)
    {
        if (!$this->keys[$code])
            return false;

        unset($this->keys[$code]);
        return true;
    }

    public function add($code, $alias)
    {
        if (!$this->keys[$code]) {
            $this->update = true;
            $this->keys[$code] = array();
        }

        $alias = strtolower($alias);
        if (!in_array($alias, $this->keys[$code])) {
            $this->update = true;
            $this->keys[$code][] = $alias;
        }

        return $this;
    }

    public function commit()
    {
        if (!$this->update)
            return false;

        $cache = new Cache(CacheDirectory, time(), '.json');
        $cache->setCache('qakeylist', $this->keys);

        $this->update = false;

        return true;
    }
}
