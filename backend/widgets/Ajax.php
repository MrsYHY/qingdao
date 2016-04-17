<?php
/**
 * User: yoyo
 * Date: 15-5-24
 * Time: 下午8:38
 */

namespace backend\widgets;


use yii\base\Widget;
use yii\helpers\Json;

class Ajax extends Widget{

    public $options = [];

    public function init(){
        parent::init();
        $options = [
            'url'=>'#',
            'type'=>'POST',
            'data'=>'{}',
            'success'=>'function(){}',
            'error'=>'function(xmlHttpRequest,e){
            showToast(xmlHttpRequest.statusText,xmlHttpRequest.responseText,"error");
        }',
        ];
        $this->options = array_merge($options,$this->options);
    }

    public function run()
    {
        $js = '$.ajax('.Json::encode($this->options).');';;
        return $js;
    }

} 