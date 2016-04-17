<?php
/**
 * User: yoyo
 * Date: 15-5-31
 * Time: 上午10:26
 */

namespace backend\assets;

use yii\web\AssetBundle;

class LtIE9Asset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $js = [
        'plugins/respond.min.js',
        'plugins/excanvas.min.js'
    ];


    public $jsOptions = [
        'condition' => 'if lt IE 9'
    ];


}