<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-8
 * Time: 下午10:29
 */
namespace backend\widgets\metronic\grid;

use yii\grid\ActionColumn;
use Yii;
use yii\helpers\Html;

class userDefinedActionColumn extends ActionColumn{
    public $suffix_template = '';

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons[$this->suffix_template.'view'])) {
            $this->buttons[$this->suffix_template.'view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons[$this->suffix_template.'update'])) {
            $this->buttons[$this->suffix_template.'update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons[$this->suffix_template.'delete'])) {
            $this->buttons[$this->suffix_template.'delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
            };
        }
    }
}