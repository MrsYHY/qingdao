<?php
/**
 * User: yoyo
 * Date: 15-5-27
 * Time: 下午11:35
 */

namespace backend\widgets\metronic;

use backend\widgets\Ajax;
use yii\base\Widget;
use yii\helpers\Html;
use yii;

/**
 * 复选框切换按钮
 * @package backend\widgets\metronic
 */
class SwitchCheckBox extends Widget{

    /**
     * @var array 表单属性值配置
     */
    public $options;

    /**
     * @var bool 是否选中
     */
    public $isCheck;
    /**
     * @var array ajax 配置 空则不使用ajax
     * @see backend\widgets\Ajax
     */
    public $ajaxOptions = [];

    /**
     * @var  \yii\base\Model 关联的model
     */
    public $model;

    /**
     * @var string 关联的model属性名
     */
    public $attribute;


    /**
     * 初始化
     */
    public function init()
    {
        /** 表单默认配置 **/
        $options  = [
            'class'=>'make-switch',
            'id'=>'SwitchCheckBox_'.$this->getId(),
            'data-on-text'=>'是',//SwitchCheck 配置
            'data-off-text'=>'否',//SwitchCheck 配置
            'data-size'=>'small',//SwitchCheck 配置
            'data-on-color'=>'success',//SwitchCheck 配置
            'data-send-ajax'=>(int)(bool)$this->ajaxOptions,//是否启用ajax
            'data-pk'=>is_subclass_of($this->model, '\yii\db\ActiveRecord')?$this->model->getPrimaryKey():0,//启用ajax时主键参数
        ];
        if(!isset($this->options['name']) && $this->model)
            $this->options['name'] = Html::getInputName($this->model,$this->attribute);
        $this->options = array_merge($options,$this->options);
        if($this->ajaxOptions)//是否启用ajax
            $this->registerJs();
        if($this->isCheck === null) {
            $this->isCheck = $this->model !== null ? $this->model->{$this->attribute} : false;
        }
        $this->view->registerCssFile(Yii::getAlias('@web/themes/metronic/').'plugins/bootstrap-switch/css/bootstrap-switch.min.css');
        $this->view->registerJsFile(Yii::getAlias('@web/themes/metronic/').'plugins/bootstrap-switch/js/bootstrap-switch.min.js');

    }

    public function run()
    {
        return Html::checkbox($this->options['name'], $this->isCheck, $this->options);
    }

    /**
     * 注册 ajax 相关js
     * @throws \Exception
     */
    private function registerJs(){

        if(isset($this->getView()->js[yii\web\View::POS_READY]) &&
            isset($this->getView()->js[yii\web\View::POS_READY]['SwitchCheckBox_JS']))//不重复加载js
            return ;
        /** ajax 配置 **/
        $ajaxOptions = [
            'data'=>new yii\web\JsExpression('data'),
            'error'=>new yii\web\JsExpression('function(xmlHttpRequest,e){
            showToast(xmlHttpRequest.statusText,xmlHttpRequest.responseText,"error");
            switchInpug.data("isSwitchFail",true);
            switchInpug.bootstrapSwitch("toggleState");
        }')
        ];
        $this->ajaxOptions = array_merge($ajaxOptions,$this->ajaxOptions);
        $ajax = Ajax::widget(['options'=>$this->ajaxOptions]);
        $js = <<<js
          $(".make-switch").on('switchChange.bootstrapSwitch', function(){
                var switchInpug = $(this);
                if(!switchInpug.data('send-ajax'))
                    return;
                var data = $.extend($(this).data("ajax-data"),{"value":$(this).attr("checked")?1:0,"pk":$(this).data("pk")});
                if(!switchInpug.data('isSwitchFail'))
                    $ajax
                else
                    switchInpug.data('isSwitchFail',false);
          });
js;
        $this->getView()->registerJs($js,yii\web\View::POS_READY,'SwitchCheckBox_JS');
    }

} 