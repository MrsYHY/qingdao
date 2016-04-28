<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午8:52
 */

namespace backend\services;

use backend\config\SystemConfig;
use backend\forms\WeChatForm;
use common\activeRecords\Activitys;
use common\activeRecords\Devices;
use common\activeRecords\LuckDrawResult;
use common\activeRecords\Prizes;
use common\activeRecords\TerminalUser;
use common\components\Tool;
use common\exceptions\DbException;
use common\services\BaseService;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\db\Connection;

class WcSiteService extends WeChatService{

    static $PRIZE_FOR_IMAGE = ['QINGDAO'=>'qingdao','IWATCH'=>'watch'];

    /**
     * 摇奖请求
     */
    public function luckDraw(WeChatForm $weChatForm){

        if ($weChatForm->open_id !== $weChatForm->user_token) {
            TerminalUser::deleteAll(['terminal_user_token'=>$weChatForm->user_token]);
            $weChatForm->user_token = null;
        }

        //判断是否关注
        $user_token = $weChatForm->open_id;
        $user = TerminalUser::getByTerminalUserToken($user_token);
        if (empty($user_token)){

        }
        if (date("Y-m-d",$user->last_luck_draw_time) !== date('Y-m-d',time())){
            $user->last_luck_draw_time = date('Y-m-d',time());
            $user->draw_luck_num = 0;
        }
        if ($user->draw_luck_num > SystemConfig::LUCK_DRAW_TOTAL){
            $this->failByJson("没有机会摇奖了！");
        }

        if ($user->draw_luck_num == -1){
            $this->failByJson("您没有机会抽奖咯");
        }

        //判断活动是否合法
        $activity_id = $weChatForm->activity_id;
        $activity = Activitys::getByIdAndTime($activity_id);
        if (empty($activity)) {
            $this->failByJson('活动已经结束啦！');
        }

              //判断设备id是否合法
        $device_id = $weChatForm->device_id;
        $device = Devices::fingByDeviceId($device_id);
        if (empty($device)) {
            $this->failByJson('不存在该设备！');
        }

        $prizes = Prizes::getByActivityId($activity_id);
        if (empty($prizes)) {
            $this->failByJson('本次活动还没有设置奖品哦！');
        }

        $win = [];
        $level_id = [];
        $winSum = 0;
        foreach ( $prizes as $p ) {
            $win [$p->prize_level] = floor(($p->win_rate*10000)%10000);
            $level_id [$p->prize_level] = $p->id;
            $winSum += floor(($p->win_rate*10000)%10000);
        }
        $win [-1] = 10000 - $winSum;
        $luckDraw = array();
        foreach ($win as $key=>$value )
        {
            for($j = 0; $j < $value; $j++){
                $luckDraw[]=$key;
            }
        }
        shuffle($luckDraw);shuffle($luckDraw);shuffle($luckDraw);
        $k = $luckDraw[array_rand($luckDraw)];
        if ($k == -1){
            $user->last_luck_draw_time = time();
            $user->draw_luck_num = $user->draw_luck_num +1;
            $user->sign_in_num = 0;
            if (!$user->save()){

            }

            $luckDrawResult = new LuckDrawResult();
            $luckDrawResult->user_id = TerminalUser::getByTerminalUserToken($user_token)->id;
            $luckDrawResult->result = LuckDrawResult::NOT_ZHONG;
            $luckDrawResult->activity_id = $activity_id;
            $luckDrawResult->prize_id = 0;
            $luckDrawResult->prize_level = -1;
            $luckDrawResult->created_at = date("Y-m-d H:i:s",time());
            $luckDrawResult->device_id = $device->id;
            $luckDrawResult->is_award = -1;
            $luckDrawResult->win_code = '';
            if (!$luckDrawResult->save()){

            }
            $res = new \stdClass();
            $res->user_token = $user_token;
            $res->activity_id = $activity_id;
            $res->device_id = $device_id;
            $res->result = $luckDrawResult->id;
            $this->successByJson($res);
        }
        try{
            $resultId = \Yii::$app->db->transaction(function()use($device,$user_token,$level_id,$activity_id,$k){
                $prizeId = $level_id[$k];
                $sql = "SELECT * FROM prizes where id={$prizeId} for update";
                $prizes = Prizes::findBySql($sql)->one();
                if (empty($prizes)){
                    throw new DbException("找不到该奖品，奖品id为".$prizeId);
                }
                if ($prizes->num == 0) {
                    throw new DbException("该奖品被摇光啦！",1);
                }
                $prizes->num = $prizes->num -1;
                if (!$prizes->save()) {
                    throw new DbException("奖品库存保存失败啦",2);
                }

                $user = TerminalUser::getByTerminalUserToken($user_token);
                if (empty($user)){
                    throw new DbException('系统找不到您的信息',3);
                }
                $user->draw_luck_num = $user->draw_luck_num + 1;;
                $user->last_luck_draw_time = time();
                $user->sign_in_num = 0;
                if (!$user->save()){
                    throw new DbException('保存您的信息失败了',5);
                }

                $luckDrawResult = new LuckDrawResult();
                $luckDrawResult->user_id = $user->id;
                $luckDrawResult->result = LuckDrawResult::ZHONG;
                $luckDrawResult->activity_id = $activity_id;
                $luckDrawResult->prize_id = $prizes->id;
                $luckDrawResult->prize_level = $k;
                $luckDrawResult->created_at = date("Y-m-d H:i:s",time());
                $luckDrawResult->device_id = $device->id;
                $luckDrawResult->is_award = LuckDrawResult::NOT_AWARD;
                $luckDrawResult->win_code = Tool::randAbc(8).date("His",time());
                if (!$luckDrawResult->save()){
                    throw new DbException('生成中奖纪录失败',4);
                }
                return $luckDrawResult->id;
            });
        }catch (Exception $e){
            $this->failByJson($e->getMessage());
        }
        $res = new \stdClass();
        $res->user_token = $user_token;
        $res->activity_id = $activity_id;
        $res->device_id = $device_id;
        $res->result = $resultId;
        $this->successByJson($res);
    }

    public function comfirm($params){
        $luckDrawResult = LuckDrawResult::findByPk($params['result_id']);
        if (empty($luckDrawResult)) {
            return '没有中奖纪录';
        }
        if ($luckDrawResult->result == LuckDrawResult::NOT_ZHONG){
            return "该纪录没有中奖哈";
        }
        if ($luckDrawResult->is_award == LuckDrawResult::AWARD) {
            return '您已经兑奖过了!';
        }
        $luckDrawResult->is_award = LuckDrawResult::AWARD;
        if ($luckDrawResult->save()){
            return $luckDrawResult;
        }
        return '更新中奖纪录失败!';
    }

} 
