<?php

namespace backend\assets;

use yii\web\AssetBundle;

class FontAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $css = [
        //'css/fonts.css',
        'css/fonts.googleapis.com.css?family=Open+Sans:400,300,600,700&subset=all',
        'plugins/font-awesome/css/font-awesome.min.css',
    ];
}
