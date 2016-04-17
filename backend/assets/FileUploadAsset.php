<?php
/**
 * User: yoyo
 * Date: 15-6-14
 * Time: 下午5:35
 */
namespace backend\assets;

class FileUploadAsset extends \yii\web\AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $css = [
        'plugins/jquery-file-upload/css/jquery.fileupload.css',
        'plugins/jquery-file-upload/css/jquery.fileupload-ui.css',
        'plugins/fancybox/source/jquery.fancybox.css',
//        'plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7',
    ];

    public $js = [
        'plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js',
        'plugins/jquery-file-upload/js/vendor/tmpl.min.js',
        'plugins/jquery-file-upload/js/vendor/load-image.min.js',
        'plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js',
        'plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js',
        'plugins/jquery-file-upload/js/jquery.iframe-transport.js',
        'plugins/jquery-file-upload/js/jquery.fileupload.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-process.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-image.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-audio.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-video.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-validate.js',
        'plugins/jquery-file-upload/js/jquery.fileupload-ui.js',
//        'plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js',
        'plugins/fancybox/source/jquery.fancybox.pack.js',
//        'plugins/fancybox/source/helpers/jquery.fancybox-buttons.js',
//        'plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js',
    ];
    public $depends = [
        'backend\assets\JqueryUIAsset'
    ];

}