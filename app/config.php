<?

return array(
    'info' => array(
        'version' => '0.00001',
        'questions' => 0,
        'aliases' => 0
    ),
    'site' => [
        'email' => 'supprot@qa.mainop.technology',
        'name' => 'Question-answering system'
    ],
    'database' => [
        'engine' => 'mysql',
        'databaseName' => 'dbname',
        'databaseUser' => 'ebabo201_admin',
        'databasePassword' => 'WH[gp_QREk.r;68:',
    ],
    'cache' => [
        'path' => str_replace('\\', '/', dirname(__FILE__)) . '/cache/', 
    ],
    'template' => [
        'directory' => str_replace('\\', '/', dirname(__FILE__)) . '/view/',
    ]

);
