<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "prizes".
 *
 * @property integer $id
 * @property integer $acrivity_id
 * @property string $name
 * @property integer $num
 * @property integer $prize_level
 * @property double $win_rate
 */
class Prizes extends \common\activeRecords\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prizes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'num', 'prize_level'], 'integer'],
            [['win_rate'], 'number'],
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
            'acrivity_id' => '活动id',
            'name' => '奖品名称',
            'num' => '奖品数量',
            'prize_level' => '奖品类型',
            'win_rate' => '中奖率',
        ];
    }
}
