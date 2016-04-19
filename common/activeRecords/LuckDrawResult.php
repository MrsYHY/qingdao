<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "luck_draw_result".
 *
 * @property integer $id
 * @property integer $result
 * @property integer $activity_id
 * @property integer $prize_id
 * @property integer $prize_level
 * @property string $created_at
 */
class LuckDrawResult extends \common\activeRecords\BaseActiveRecord
{
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
}
