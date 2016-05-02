<?php
/**
 * User: yoyo
 * Date: 15-6-14
 * Time: 上午11:46
 */
namespace backend\assets;
use yii\web\AssetBundle;

class DatetimePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $css = [
        'plugins/bootstrap-datepicker/css/datepicker3.css',
        'plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
    ];

    public $js = [
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js',
    ];
    public $depends = [
        'backend\assets\JqueryUIAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}