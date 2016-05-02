<?php
namespace backend\assets;

use yii\web\AssetBundle;
/**
 * User: yoyo
 * Date: 15-6-12
 * Time: 下午9:46
 */
class JqueryUIAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $css = [];
    public $js = [
        'plugins/jquery-ui/jquery-ui.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}