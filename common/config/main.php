<?php
return [
    'language'=>'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter'=>[
            'nullDisplay'=>'--',
            'dateFormat' => 'yyyy-MM-dd',
            'timeFormat' => 'HH:mm:ss',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss'
        ],
    ],
];
