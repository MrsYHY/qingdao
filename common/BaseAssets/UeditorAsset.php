<?php
/**
 * 百度开源 富文本编辑器
 * @author kouga-huang
 * @since 15-12-2 下午3:28
 */
namespace common\baseAssets;

use yii\web\AssetBundle;

class UeditorAsset extends AssetBundle{
    public $sourcePath = '@vendor/stevenyangecho/laravel-u-editor/resources/public';
    public $css = [];
    public $js = [
        'ueditor.config.js',
        'ueditor.all.js',
    ];
}
