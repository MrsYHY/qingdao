<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "sys_dictionary".
 *
 * @property integer $id
 * @property string $keyword
 * @property string $name
 * @property integer $parent
 */
class SysDictionary extends \common\activeRecords\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_dictionary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword', 'name'], 'required'],
            [['parent'], 'integer'],
            [['keyword', 'name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword' => '关键字 唯一',
            'name' => '字典描述',
            'parent' => '父字典id',
        ];
    }
}
