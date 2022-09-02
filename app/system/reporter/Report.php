<?

namespace System\Reporter;

final class Report
{
    protected $reportModule = '';
    protected $reportFile = '';
    protected $reportData = array();

    public function __construct($module)
    {
        $this->reportModule = $module;
        $this->reportFile = AppDirectory . '/reports/report-' . $this->reportModule . '.json';
        $this->reportData = $this->getdata();
    }

    public function setdata($data)
    {
        foreach ($data as $v) {
            $this->reportData[] = $v;
        }
    }

    public function commit()
    {
        file_put_contents($this->reportFile, json_encode($this->reportData));
    }

    private function getdata()
    {
        $tdata = @json_decode(file_get_contents($this->reportFile));

        if (!$tdata)
            return array();

        return $tdata;
    }
}
