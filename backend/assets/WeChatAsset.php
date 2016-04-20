<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-20
 * Time: 下午11:07
 */

namespace backend\assets;

use yii\web\AssetBundle;

class WeChatAsset extends AssetBundle{
    public $sourcePath = '@backend/web/wechat';
    public $css = [
//        'css/AdminLTE.min.css',
//        'css/skins/_all-skins.min.css'
    ];
    public $js = [
//        'js/app.min.js'
    ];

    public $depends = [
//        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
//        'backend\assets\CommonAsset'
    ];
} 