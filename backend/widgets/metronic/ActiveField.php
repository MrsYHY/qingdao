<?php
/**
 * User: yoyo
 * Date: 15-5-24
 * Time: 上午8:27
 */

namespace backend\widgets\metronic;

use backend\assets\FileUploadAsset;
use backend\assets\Select2Asset;
use backend\assets\SummernoteAsset;
use common\models\File;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii;

class ActiveField extends \yii\bootstrap\ActiveField
{

    public $inputTemplate = '<div class="col-md-4">{input}</div>';

    public $labelOptions = ['class' => 'col-md-2 control-label'];

    /**
     * 联动下拉 暂时支持持二级联动
     * @param $linkAttribute
     * @param $items
     * @param array $items2
     * @param array $options
     * @param array $options2
     * @return $this
     */
    public function linkDropDownList($linkAttribute,$items,$items2=[], $options = [], $options2 = [])
    {
        $options = array_merge($this->inputOptions, $options,['class'=>'form-control']);
        $options2 = array_merge($this->inputOptions, $options2,['class'=>'form-control']);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] ='<div class="row">';
        $this->parts['{input}'] .='<div class="col-md-6">'. Html::activeDropDownList($this->model, $this->attribute, $items, $options).'</div>';
        $this->parts['{input}'] .= '<div class="col-md-6">'.Html::activeDropDownList($this->model, $linkAttribute, $items2, $options2).'</div>';
        $this->parts['{input}'] .='</div>';
        return $this;
    }

    /**
     * 是与否切换按钮
     * @param array $options
     * @param bool $enclosedByLabel
     * @return static
     */
    public function switchCheckBox($options = [],$enclosedByLabel=false)
    {
        $return = parent::checkbox($options,$enclosedByLabel);
        $options = array_merge($options,$this->inputOptions);
        $options['class'].=' make-switch';
        $this->parts['{input}'] = SwitchCheckBox::widget([
            'model'=>$this->model,
            'attribute'=>$this->attribute,
            'options'=> $options,
        ]);
        return $return;
    }

    /**
     * 范围选择滚动条
     * @param array $rangeOption
     * @param array $options
     * @return self
     */
    public function rangeInput($rangeOption=[],$options=[])
    {
        if (!array_key_exists('id', $options)) {
            $options['id'] = BaseHtml::getInputId($this->model, $this->attribute);
        }
        $_rangeOption = ['min'=>0,'max'=>99,'step'=>1,'postfix'=>''];
        $rangeOption = ArrayHelper::merge($_rangeOption,$rangeOption);
        $optionJson = json_encode($rangeOption);
        $js = <<<JS
          $("#{$options['id']}").ionRangeSlider({$optionJson});
JS;
        $this->form->view->registerJs($js);
        $this->form->view->registerJsFile('@web/themes/metronic/'.'plugins/ion.rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js');
        $this->form->view->registerCssFile('@web/themes/metronic/'.'plugins/ion.rangeslider/css/ion.rangeSlider.Metronic.css');
        $this->form->view->registerCssFile('@web/themes/metronic/'.'plugins/ion.rangeslider/css/ion.rangeSlider.css');
        return parent::input('text',$options);
    }

    /**
     * 下拉
     * @param array $items
     * @param array $options
     * @return static
     */
    public function select2($items=[],$options=[])
    {
        $options['class'] = ArrayHelper::getValue($options,'class','').' select2me  form-control';
        $options['data-placeholder'] = '搜索';
        Select2Asset::register($this->form->view);
        $this->form->view->registerCss('.select2-drop{z-index:12000;}');
        return parent::dropDownList($items,$options);
    }

    /**
     * 图标选择
     * @param array $options
     * @return $this
     */
    public function iconSelect($options=[])
    {
        $id = $options['id'] = isset($options['id'])?$options['id']:Html::getInputId($this->model,$this->attribute);
        $value =  $this->model->{$this->attribute};
        $icon_array = require_once(Yii::$app->basePath.'/config/icon_class.php');
        $icon_array = [''=>'请选择']+$icon_array[0]+$icon_array[1]+$icon_array[2];
        $icon_array_json = json_encode($icon_array);
        $js = <<<JS
         var iconList = {$icon_array_json};
         $(document).ready(function(){
             $("#{$id}").select2({
                 placeholder: "选择图标",
                 allowClear: true,
                 formatResult: format,
                 formatSelection: format,
                 escapeMarkup: function (m) {
                     return m;
                 }
             });
        });
        function format(state) {
            if (!state.id) return state.text; // optgroup
            return '<i class="'+state.text+'"></i>' + state.text;
        }
JS;
        $this->form->view->registerJs($js);
        $this->form->view->registerJsFile($this->form->view->theme->baseUrl.'/plugins/select2/select2.min.js');
        $this->form->view->registerCssFile($this->form->view->theme->baseUrl.'/plugins/select2/select2.css');
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeDropDownList($this->model, $this->attribute, $icon_array,$options);
        return $this;
    }

    /**
     * 多选select
     * @param array $data
     * @param array $options
     * @return $this
     */
    public function multiSelect($data=[],$options = [])
    {

        $this->parts['{input}'] = MultiSelect::widget(array_merge($options, ['data'=>$data,'model' => $this->model, 'attribute' => $this->attribute]));
        return $this;
    }

    /**
     * 编辑器
     * @param array $options
     * @param array $wOptions
     * @return static
     */
    public function summernoteEditor($options = [],$wOptions = [])
    {
        if(!isset($options['inputTemplate']))
            $this->inputTemplate = '<div class="col-md-9"><div class="summernote_div"></div>{input}</div>';
        $options = ArrayHelper::merge(['id'=>Html::getInputId($this->model,$this->attribute)],$options);
        SummernoteAsset::register($this->form->getView());
        //$wOptions['stylesheets'] = [$this->form->view->theme->baseUrl.'/plugins/bootstrap-wysihtml5/wysiwyg-color.css'];
        //$wOptions['locale'] = 'zh-cn';
        $config = json_encode($wOptions);
        $js = <<<js
        var config = {$config}
        config['lang']='zh-CN';
        config['height']='300';
        $('#{$options['id']}').summernote(config); //;
        $('#{$options['id']}').parent().find('button[data-event="showImageDialog"],button[data-event="showVideoDialog"]').removeAttr('data-event').on('click',function(){
           var name = "{$options['id']}_fileupload_form";
           $("[href=\'#"+name+"\']").click();
        });
js;
        $checkFileJs='function(idList,imgs){
            var id = "#'.$options['id'].'";
            for(var i in imgs){
                var imgHtml = "<images data-file-id =\""+idList[i]+"\" src=\""+imgs[i].url+"\" alt=\""+imgs[i].name+"\" />";
                $(id).parent().find(".note-editable").append($(imgHtml));
            }
        }';

        $widget = FileUploadWidget::widget(['id'=>$options['id'].'_fileupload','onCheckFile'=>new yii\web\JsExpression($checkFileJs)]);
        $this->form->view->registerJs($js);
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeTextarea($this->model, $this->attribute, $options).'<div style="display:none">'.$widget.'</div>';
        return $this;
    }

    /**
     * 带长度提示的富文本框
     * @param array $options
     * @return static
     */
    public function textarea($options= []){
        if(isset($options['maxlength'])){
            $options['class'] = ArrayHelper::getValue($options,'class',' ').' form-control maxlength-handler';
            $options = ArrayHelper::merge(['id'=>Html::getInputId($this->model,$this->attribute)],$options);
            $this->form->view->registerJsFile($this->form->view->theme->baseUrl.'/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js');
            $this->form->view->registerJs('$("#'.$options['id'].'").maxlength({alwaysShow: true});');
        }
        return parent::textarea($options);
    }

    /**
     * 日期输入
     * @param array $datetimeOption
     * @param array $options
     * @return static
     */
    public function datetimePicker($datetimeOption= [],$options=[]){

       \backend\assets\DatetimePickerAsset::register($this->form->view);
        $id = Html::getInputId($this->model,$this->attribute);
        $option= ['id'=>$id.'_datetime','class'=>'date-picker form-control'];
        $option = array_merge($option,$options);
        $datetimeOption = array_merge(['orientation'=>'left','language'=>'zh-CN','autoclose'=> true],$datetimeOption);
        $datetimeOption = json_encode($datetimeOption);
        $value = $this->model->{$this->attribute};
        $date = $value?date('Y年m月d日',$value):'';
        $dateInput = Html::textInput($option['id'],$date,$option);
        $this->form->view->registerJs('
            $("#'.$id.'_datetime").datepicker('.$datetimeOption.');
            $("#'.$id.'_datetime").change(function(){
                var date =  $(this).val().replace(/[^0-9]/g,"/");
                var unix_time  = new Date(date);
                $("#'.$id.'").val(unix_time.getTime().toString()/1000);
            });
       ');
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeHiddenInput($this->model,$this->attribute).$dateInput;
        return $this;
    }

    /**
     * tag标签输入
     * @param array $options
     * @return static
     */
    public function tagsInput($options = []){
        $this->form->view->registerJsFile($this->form->view->theme->baseUrl.'/plugins/jquery-tags-input/jquery.tagsinput.min.js');
        $this->form->view->registerCssFile($this->form->view->theme->baseUrl.'/plugins/jquery-tags-input/jquery.tagsinput.css');
        //$this->form->view->registerCss('div.tagsinput span.tag {background: #26a69a;}');
        $options['id'] = isset($options['id'])?$options['id']:Html::getInputId($this->model,$this->attribute);
        $js = <<<JS
    \$(document).ready(function(){
        \$('#{$options['id']}').tagsInput({
            'width': 'auto',
            'defaultText':'请输入...',
            'onAddTag': function () {}
        });
    });
JS;
        if(!isset($options['inputTemplate']))
            $this->inputTemplate = '<div class="col-md-9">{input}</div>';
        $this->form->view->registerJs($js);
        return parent::textInput($options);
    }

    /**
     * 带单位的输入框
     * @param $unit
     * @param array $options
     * @return static
     */
    public function unitTextInput($unit,$options = [])
    {
        $this->inputTemplate = '<div class="col-md-4"><div class=" input-group">{input}<span class="input-group-addon">'.$unit.'</span></div></div>';
        return parent::textInput($options);
    }

    /**
     * 上传部件
     * @param array $uploadOptions
     * @param array $widgetOptions
     * @param array $options
     * @return $this
     */
    public function uploadFileInput($uploadOptions=[],$widgetOptions=[],$options=[])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $options['id'] = Html::getInputId($this->model,$this->attribute);
        $fileModels = File::find()->where(['id'=>explode(',',$this->model->{$this->attribute})])->all();
        $files = [];
        foreach($fileModels as $file){
            $files[] = [
                'url'=>yii\helpers\Url::toRoute(['file/view','id'=> $file->id]),
                'thumbnail_url'=>yii\helpers\Url::toRoute(['file/preview','id'=> $file->id]),
                'id'=> $file->id,
                'name'=>$file?($file->description.($file->status==File::STATUS_DELETE?'(已删除)':'')):'',
                'size'=>$file?$file->file_size:0,
                'delete_url'=>yii\helpers\Url::toRoute(['file/delete-file','id'=>$file->id]),
                'delete_type'=>'DELETE'
            ];
        }
        $fileUploadOptions = array_merge(['maxNumberOfFiles'=>1],$uploadOptions);
        $widgetOptions = array_merge(['onCheckFile'=>$this->attribute.'SetFile','initFileIdList'=>['files'=>$files]],$widgetOptions);
        $widgetOptions['uploadOptions'] = &$fileUploadOptions;
        $widget = FileUploadWidget::widget($widgetOptions);
        $this->form->view->registerJs('function '.$this->attribute.'SetFile(idlist){$("#'.$options['id'].'").val(idlist.join(","))}',yii\web\View::POS_END);
        $this->parts['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $options).$widget;
        return  $this;
    }

    public function externalFileInput($options=[])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $options['id'] = Html::getInputId($this->model,$this->attribute);
        $file = File::findOne($this->model->{$this->attribute});
        $input = Html::textInput($this->attribute.'_ext',$file?$file->url:(Yii::$app->request->post('apk_baidu_ext','')),$options);
        $js=<<<JS
        $("[name='{$this->attribute}_ext']").change(function(){
            if($(this).val()=="")
                $("[name='{$this->attribute}']").val('')
            else
                $("[name='{$this->attribute}']").val('0')
        });
JS;
        $this->form->view->registerJs($js);
        $this->parts['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $options).$input;
        return  $this;
    }

    public function colorInput($options=[])
    {
        $options = $options+['class'=>'colorpicker-rgba form-control'];
        return parent::textInput($options);
    }
} 