<?php
/**
 * 
 * @author: shenchao
 * @since: 2016-04-26
 */

namespace backend\forms;


use common\forms\BaseForm;

class ZonesForm extends BaseForm{

    public $id;
    public $name;

    public $isNewRecord = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'required','on'=>'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '大区名称',
        ];
    }
} 