<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "luck_draw_result".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $result
 * @property integer $activity_id
 * @property integer $prize_id
 * @property integer $prize_level
 * @property integer $device_id
 * @property string $created_at
 * @property integer $is_award
 */
class LuckDrawResult extends \common\activeRecords\BaseActiveRecord
{
    const AWARD = 0;//已兑奖
    const NOT_AWARD = 1;//没有兑奖

    const ZHONG = 1;//中奖
    const NOT_ZHONG = 0;//未中奖
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'luck_draw_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result', 'activity_id', 'prize_id', 'prize_level'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'result' => 'Result',
            'activity_id' => 'Activity ID',
            'prize_id' => 'Prize ID',
            'prize_level' => 'Prize Level',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 判断是否还有未兑奖的记录 只拿出一条
     * @param $userId
     */
    public static function getNotAwardByUserId($userId){
        $result = self::find()->where(['user_id'=>$userId,'is_award'=>self::NOT_AWARD,'result'=>self::ZHONG])->one();
        if ( empty($result)){
            return false;
        }
        return $result;
    }
}
