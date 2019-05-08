<?php
return [
    'webuploader' => [
        // 后端处理图片的地址，value 是相对的地址
        'uploadUrl' => 'product/upload',
        // 多文件分隔符
        'delimiter' => ',',
        // 基本配置
        'baseConfig' => [
            'defaultImage' => 'http://img1.imgtn.bdimg.com/it/u=2056478505,162569476&fm=26&gp=0.jpg',
            'disableGlobalDnd' => true,
            'accept' => [
                'title' => 'Images',
                'extensions' => 'gif,jpg,jpeg,bmp,png',
                'mimeTypes' => 'image/*',
            ],
            'pick' => [
                'multiple' => false,
            ],
            'fileSizeLimit'=>500 * 1024 * 1024,//所有文件上传的大小限制,单位字节
            'fileSingleSizeLimit'=>2 * 1024 * 1024,//单张图片上传限制大小，单位字节    
            'fileNumLimit'=>20,//文件上传数量限制
            'threads'=>1//上传并发数。允许同时最大上传进程数,为了保证文件上传顺序
        ],
    
    ],
    'imageUploadRelativePath' => './uploads/', // 图片默认上传的目录
    'imageUploadSuccessPath' => '/uploads/', // 图片上传成功后，路径前缀
];
