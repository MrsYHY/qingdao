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
 * @property string $sale_name
 * @property string $zone_id
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
            [['device_keyword', 'user_id','zone_id'], 'required'],
            [['user_id', 'shake_num'], 'integer'],
            [['device_name'], 'string', 'max' => 50],
            [['device_keyword','sale_name'], 'string', 'max' => 255],
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
            'sale_name'=>'促销员',
            'zone_id'=>'所在大区',
        ];
    }

    public static function findByPk($pk){
        $ra = Devices::find()->where(['id'=>$pk])->one();
        if(empty($ra)){
            return false;
        }
        return $ra;
    }

    /**
     * 通过微信提供的设备id
     */
    public static function fingByDeviceId($deviceId){
        $ra = Devices::find()->where(['device_keyword'=>$deviceId])->one();
        if(empty($ra)){
            return false;
        }
        return $ra;
    }
}
