<?php

namespace common\activeRecords;

use Yii;

/**
 * This is the model class for table "prizes".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property string $name
 * @property string $keyword
 * @property integer $num
 * @property integer $prize_level
 * @property double $win_rate
 */
class Prizes extends \common\activeRecords\BaseActiveRecord
{
    public $activityName;

    static  $prizeLevel = [
        0=>'特等奖',
        1=>'一等奖',
        2=>'二等奖',
        3=>'三等奖',
        4=>'四等奖',
        5=>'五等奖',
    ];

    static $prizes = [
        'IWATCH'=>'苹果iWatch',
        'QINGDAO'=>'青岛啤酒',
        'CHONGDIANBAO'=>'充电宝',
        'DAHUOJI'=>'经典1903打火机',
        'PIJIUBOLITAOBEN'=>'青岛啤酒玻璃套杯',
        'IPONE6S'=>'IPONE6S'];

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
            ['keyword','safe'],
            [['num', 'prize_level'], 'integer'],
            [['win_rate'], 'number','min'=>0,'max'=>1],
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
            'activity_id' => '活动id',
            'activityName' => '活动主题',
            'keyword'=>'奖品关键字',
            'name' => '奖品名称',
            'num' => '奖品数量',
            'prize_level' => '奖品等级',
            'win_rate' => '中奖率',
        ];
    }
    public static function findByPk($pk){
        $ra = Prizes::find()->where(['id'=>$pk])->one();
        if(empty($ra)){
            return false;
        }
        return $ra;
    }
}
