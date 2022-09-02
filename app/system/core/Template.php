<?

namespace System\Core;

use Exception;

class Template
{

    protected $dir;
    protected $template;
    protected $variables = array();

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function render($template, $array = [])
    {
        $this->template = $template;

        if (!file_exists($this->dir . $this->template))
            throw new Exception('Page not found', 404);

        foreach ($array as $key => $value) {
            $this->variables[$key] = $value;
        }

        extract($this->variables);
        chdir(dirname($this->dir . $this->template));
        ob_start();

        include basename($this->dir . $this->template);

        return ob_get_clean();
    }

    public function __get($key)
    {
        return $this->variables[$key];
    }

    public function __set($key, $value)
    {
        $this->variables[$key] = $value;
    }
}
