<?php
/**
 * User: yoyo
 * Date: 15-5-31
 * Time: 下午9:49
 */

namespace backend\assets;


use yii\web\AssetBundle;

class Wysihtml5Asset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $js = [
        'plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js',
        'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js',
        'plugins/bootstrap-wysihtml5/locales/bootstrap-wysihtml5.zh-CN.js',
    ];



    public $css = [
        'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css',
        'plugins/bootstrap-wysihtml5/wysiwyg-color.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];

} 