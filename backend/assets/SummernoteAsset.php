<?php
namespace backend\assets;


use yii\web\AssetBundle;
/**
 * User: yoyo
 * Date: 15-6-11
 * Time: 下午10:43
 */
class SummernoteAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $js = [
        'plugins/bootstrap-summernote/summernote.js',
        'plugins/bootstrap-summernote/lang/summernote-zh-CN.js',
    ];



    public $css = [
        'plugins/bootstrap-summernote/summernote.css',
    ];

    public $depends = [
        'backend\assets\JqueryUIAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}