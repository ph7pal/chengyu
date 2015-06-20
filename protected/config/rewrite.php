<?php

return array(
    'urlFormat' => 'path', //get
    'showScriptName' => false, //隐藏index.php   
    'urlSuffix' => '.html', //后缀   
    'rules' => array(
        'destinations' => 'index/all',
        'area-<areaid:\d+>' => 'index/show',
        
        'posts' => 'posts/all',
        'posts/col-<areaid:\d+>' => 'posts/all',
        'posts/tag-<tagid:\d+>' => 'posts/all',
        'post/<id:\d+>' => 'posts/index',
        
        'questions-<areaid:\d+>' => 'question/index',
        'questions-tag-<tagid:\d+>' => 'question/index',
        'questions' => 'question/index',        
        'question/<id:\d+>' => 'question/view',
        'answer/<id:\d+>' => 'question/answer',    
        
        'positions-<areaid:\d+>' => 'position/index',
        'positions' => 'position/index',   
        'poi/<id:\d+>' => 'position/view',
        'upload/<id:\d+>' => 'position/upload',
        'gallery/<id:\d+>' => 'position/images',
        'poi/image-<id:\d+>' => 'position/image',
        'poi/map-<id:\d+>' => 'position/map',
        'poi/area-<areaid:\d+>' => 'position/areamap',
        'poi/post-<id:\d+>' => 'poipost/view',
        
        'map/<id:\d+>' => 'map/show',        
        'user/<id:\d+>' => 'users/index',
        'image/<id:\d+>' => 'attachments/index',    
        
        'journals-<areaid:\d+>' => 'travel/index',
        'journals' => 'travel/index',
        'journal/add' => 'travel/create',
        'journal/<id:\d+>' => 'travel/view',
        
        'stories-<areaid:\d+>' => 'posts/story',
        'stories' => 'posts/story',        
        'tags' => 'tags/all', 
        //约伴
        'yueban-<areaid:\d+>' => 'yueban/index',
        'yueban' => 'yueban/index',
        //商品
        'goods-<areaid:\d+>' => 'goods/index',
        'goods' => 'goods/index',  
        'goods/<id:\d+>' => 'goods/detail',    
        //站点信息
        'siteinfo/<code:\w+>' => 'siteinfo/view',
        'sitemap<id:\d+>.xml' => 'site/sitemap',
        'sitemap.xml' => 'site/sitemap',
        'url/<code:\w+>' => 'redirect/to',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    )
);
?>
