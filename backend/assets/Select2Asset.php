<?php
/**
 * User: yoyo
 * Date: 15-5-31
 * Time: 下午8:52
 */

namespace  backend\assets;


use yii\web\AssetBundle;

class Select2Asset  extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $js = [
        'plugins/select2/select2.min.js',
    ];

    public $css = [
        'plugins/select2/select2.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

} 