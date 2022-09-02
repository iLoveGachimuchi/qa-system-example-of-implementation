<?php

return [
    '/' => 'Methods\PublicMethods@test',
    '/info'  => 'Methods\PublicMethods@info',
    '/test'  => 'Methods\PublicMethods@test',
    '/question'  => 'Methods\PublicMethods@question',
    '/answer'  => 'Methods\PublicMethods@answer',


    '/:token/info'  => 'Methods\PrivateMethods@info',
    '/:token/test'  => 'Methods\PrivateMethods@test',
    '/:token/question'  => 'Methods\PrivateMethods@question',

    '/:token/chat/test'  => 'Methods\PrivateMethods@chat',

    '/ui/qa/learning' => 'UI\QALearning@index',
    '/ui/qa/learning/questions' => 'UI\QALearning@questions',
    '/ui/qa/learning/answers' => 'UI\QALearning@answers',

    '/ui/qa/learning/question/add' => 'UI\QALearning@createQuestion',
    '/ui/qa/learning/answer/add' => 'UI\QALearning@createAnswer',

];