<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace  backend\assets;

use yii\web\AssetBundle;

/**
 * SpinnerAsset for spinner widget.
 */
class MultiSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/metronic';

    public $js = [
        'plugins/jquery-multi-select/js/jquery.multi-select.js',
    ];

    public $css = [
        'plugins/jquery-multi-select/css/multi-select.css',
    ];


    public $depends = [
        'backend\assets\AppAsset',
    ];
}
