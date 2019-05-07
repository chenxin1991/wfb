<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/lightbox.min.css'
    ];
    public $js = [
        'js/app.js',
        'js/lightbox.min.js'
  
    ];
    public $depends = [
        'kartik\select2\Select2Asset',
        'kartik\select2\ThemeDefaultAsset',
        'dmstr\web\AdminLteAsset'
    ];
}
