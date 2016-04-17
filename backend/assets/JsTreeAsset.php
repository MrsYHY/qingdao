<?php
/**
 * User: yoyo
 * Date: 15-6-1
 * Time: 下午9:45
 */

namespace backend\assets;


use yii\web\AssetBundle;

class JsTreeAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $js = [
        'plugins/jstree/dist/jstree.min.js',
    ];

    public $css = [
        'plugins/jstree/dist/themes/default/style.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

} 