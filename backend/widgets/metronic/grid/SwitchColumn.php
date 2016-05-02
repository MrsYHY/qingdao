<?php
/**
 * User: yoyo
 * Date: 15-5-24
 * Time: 下午7:17
 */

namespace backend\widgets\metronic\grid;

use backend\widgets\metronic\SwitchCheckBox;
use backend\widgets\metronic\SwitchInput;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\grid\Column;
use yii\helpers\Inflector;
use yii;

class SwitchColumn extends Column{

    public $attribute;
    public $options = [];
    public $ajaxOptions = [];
    private $_model;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return $this->getDataCellValue($model, $key, $index);
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }
    }

    public function init()
    {
        parent::init();
        $provider = $this->grid->dataProvider;
        if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
            /* @var $model Model */
            $this->_model = new $provider->query->modelClass;
            if($this->header === null)
                $this->header = $this->_model->getAttributeLabel($this->attribute);

        } else {
            $models = $provider->getModels();
            if (( $this->_model = reset($models)) instanceof Model) {
                /* @var $model Model */
                if($this->header === null)
                    $this->header =  $this->_model->getAttributeLabel($this->attribute);
            } else {
                if($this->header === null)
                    $this->header = Inflector::camel2words($this->attribute);
            }
        }

    }

    /**
     * @param yii\db\ActiveRecord $model
     * @param $key
     * @param $index
     * @return string
     */
    public function getDataCellValue($model, $key, $index)
    {
        $this->options = $this->options;
        return SwitchCheckBox::widget([
            'model'=>$model,
            'attribute'=>$this->attribute,
            'options'=> $this->options,
            'ajaxOptions'=>$this->ajaxOptions,
        ]);
    }
} 