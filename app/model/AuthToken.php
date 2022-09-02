<?

namespace Model;

use Exception;

final class AuthToken
{

    protected $tokenStorage = null;
    protected $tokenStorageDir = '';

    public function __construct()
    {
        $this->tokenStorageDir = AppDirectory . '/cache/tokenStorage.dat';

        if (!file_exists($this->tokenStorageDir))
            file_put_contents($this->tokenStorageDir, '');

        $this->tokenStorage = $this->tokenStorageLoad(file_get_contents($this->tokenStorageDir));
    }
    public function haveAccess($token, $code = null)
    {
        if (!$this->tokenStorage[$token])
            return false;

        if ($code !== null)
            if (!in_array('*', $this->tokenStorage[$token]) and !in_array($code, $this->tokenStorage[$token]))
                return false;

        return true;
    }

    private function tokenStorageLoad($tokenstoragetext)
    {
        $list = explode('|', $tokenstoragetext);
        $list2 = array();

        foreach ($list as $token) {
            if (strlen($token) <= 1)
                continue;

            $t = substr($token, 0, strpos($token, '('));

            $list2[$t] = array();

            if (strpos($token, '(') > 1 or strpos($token, '*') > 1) {
                $p = str_replace(')', '', substr($token, strpos($token, '(') + 1));

                $list2[$t] = explode(',', $p);
            } else
                $list2[$t][] = '*';
        }

        return $list2;
    }
}
