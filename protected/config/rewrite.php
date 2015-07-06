<?php

return array(
    'urlFormat' => 'path', //get
    'showScriptName' => false, //隐藏index.php   
    'urlSuffix' => '', //后缀   
    'rules' => array(
        'search' => 'chengyu/search',
        'dict-<char:\w+>-<page:\d+>' => 'chengyu/index',
        'dict-<page:\d+>' => 'chengyu/index',
        'dict-<char:\w+>' => 'chengyu/index',
        'dict' => 'chengyu/index',
        'detail/<id:\d+>' => 'chengyu/view',
        'story-<page:\d+>' => 'chengyu/story',
        'story' => 'chengyu/story',
        'siteinfo/<code:\w+>' => 'siteinfo/view',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
    )
);