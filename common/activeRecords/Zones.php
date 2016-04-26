<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "zones".
 *
 * @property integer $id
 * @property string $name
 */
class Zones extends \common\activeRecords\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'],'safe'],
            [['name'], 'string', 'max' => 255],
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

    public static function findByPk($pk){
        $ra = self::find()->where(['id'=>$pk])->one();
        if(empty($ra)){
            return false;
        }
        return $ra;
    }
}
