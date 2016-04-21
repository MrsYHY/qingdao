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

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        '/wechat/css/bootswatch.min.css',
    ];

    public $js = [

    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
//        'backend\assets\CommonAsset'
    ];
} 