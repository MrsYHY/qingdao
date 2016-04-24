<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-23
 * Time: 下午9:36
 */

namespace backend\forms;


use common\forms\BaseForm;

class LuckDrawResultForm extends BaseForm{

    public $id;
    public $activity_id;
    public $result;
    public $user_id;
    public $prize_id;
    public $prize_level;
    public $created_at;
    public $is_award;
    public $win_code;
    public $end_created_at;

    public $start_created_at;

    public $device_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id','id','activity_id','result','user_id','prize_id','prize_level','created_at','is_award','win_code','end_created_at','end_created_at'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'=>'id',
            'activity_id'=>'活动',
            'result'=>'摇奖结果',
            'user_id'=>'用户',
            'prize_id'=>'奖品',
            'prize_level'=>'奖品等级',
            'created_at'=>'摇奖时间',
            'is_award'=>'是否兑奖',
            'win_code'=>'兑奖码',
            'device_id'=>'设备'
        ];
    }


} 