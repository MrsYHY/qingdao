<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 下午9:16
 */

namespace backend\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/common.js',
        'js/echarts.min.js',
    ];

    public $css = [
        'css/backend.css'
    ];
}