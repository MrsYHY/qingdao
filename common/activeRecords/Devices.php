<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property integer $id
 * @property string $device_name
 * @property string $device_keyword
 * @property integer $user_id
 * @property integer $shake_num
 */
class Devices extends \common\activeRecords\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_keyword', 'user_id'], 'required'],
            [['user_id', 'shake_num'], 'integer'],
            [['device_name'], 'string', 'max' => 50],
            [['device_keyword'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_name' => '设备名称',
            'device_keyword' => '设备标识',
            'user_id' => '促销员',
            'shake_num' => '摇一摇次数',
        ];
    }

    public static function findByPk($pk){
        $ra = Activitys::find()->where(['id'=>$pk])->one();
        if(empty($ra)){
            return false;
        }
        return $ra;
    }
}
