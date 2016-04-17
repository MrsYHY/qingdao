<?php
/**
 * User: yoyo
 * Date: 15-5-30
 * Time: 上午10:47
 */

namespace backend\widgets;


class Pjax extends \yii\widgets\Pjax{

    public $timeout  = 3000;

    public function init(){
        parent::init();
        $js = <<<JS
    $("#{$this->id}").on('pjax:send', function() {
            Metronic.blockUI({
            target: $('.portlet-body'),
            animate: true,
            overlayColor: 'none'
        });
    })
    $("#{$this->id}").on('pjax:complete', function() {
        Metronic.unblockUI($('.portlet-body'));
    })
JS;
    $this->view->registerJs($js);
    }
} 