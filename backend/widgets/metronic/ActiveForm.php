<?php
/**
 * User: yoyo
 * Date: 15-5-15
 * Time: 下午10:55
 */

namespace backend\widgets\metronic;


use yii\helpers\Html;

class ActiveForm extends \yii\bootstrap\ActiveForm{

    public $fieldClass = 'backend\widgets\metronic\ActiveField';

    public $options = ['class'=>'form-horizontal'];

    public $layout = 'default';

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param array $options
     * @return \backend\widgets\metronic\ActiveField
     */
    public function field($model, $attribute, $options = []){
        return parent::field($model, $attribute, $options);
    }

    public function summary($models,$options=[])
    {
        if(\Yii::$app->session->hasFlash('success')){
            $message = \Yii::$app->session->getFlash('success');
            $js = <<<js
        showToast('操作成功','$message','success')
js;
        $this->view->registerJs($js);
        } else
            $this->errorSummary($models, $options);
    }

    public function errorSummary($models, $options = [])
    {
        $header = isset($options['header']) ? $options['header'] : '<p>' . \Yii::t('yii', 'Please fix the following errors:') . '</p>';
        //$footer = isset($options['footer']) ? $options['footer'] : '';
        $encode = !isset($options['encode']) || $options['encode'] !== false;
        unset($options['header'], $options['footer'], $options['encode']);

        $lines = [];
        if (!is_array($models)) {
            $models = [$models];
        }
        foreach ($models as $model) {
            /* @var $model Model */
            foreach ($model->getFirstErrors() as $error) {
                $lines[] = $encode ? Html::encode($error) : $error;
            }
        }

        if (empty($lines)) {
            return;
            // still render the placeholder for client-side validation use
            //$content = "<ul></ul>";
            //$options['style'] = isset($options['style']) ? rtrim($options['style'], ';') . '; display:none' : 'display:none';
        } else {
            $content = "<ul><li>" . implode("</li>\n<li>", $lines) . "</li></ul>";
        }
        $js = <<<js
        showToast('$header','$content','error');
js;
        $this->view->registerJs($js);
        return ;
    }

} 