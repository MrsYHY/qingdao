<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午8:52
 */

namespace backend\services;


use backend\forms\WeChatForm;
use common\activeRecords\Activitys;
use common\activeRecords\Devices;
use common\activeRecords\Prizes;
use common\services\BaseService;

class WcSiteService extends BaseService{

    static $PRIZE_FOR_IMAGE = ['QINGDAO'=>'qingdao','IWATCH'=>'watch'];

    /**
     * 摇奖请求
     */
    public function luckDraw(WeChatForm $weChatForm){

        //判断是否关注
        $user_token = $weChatForm->user_token;

        //判断活动是否合法
        $activity_id = $weChatForm->activity_id;
        $activity = Activitys::getByIdAndTime($activity_id);
        if (empty($activity)) {
            $this->failByJson('活动已经结束啦！');
        }

        //判断设备id是否合法
        $device_id = $weChatForm->device_id;
        $device = Devices::findByPk($device_id);
        if (empty($device)) {
            $this->failByJson('不存在该设备！');
        }

        $prizes = Prizes::getByActivityId($activity_id);
        if (empty($prizes)) {
            $this->failByJson('本次活动还没有设置奖品哦！');
        }

        //抽奖算法开始
        $k = rand(1,15);
        if ($k < 5){
            $result = new \stdClass();
            $result->user_token = $user_token;
            $result->device_id = $device_id;
            $result->activity_id = $activity_id;
            $result->prize_id = 4;
            $this->successByJson($result,1);
        }
        $this->failByJson("没有中奖",2);

    }

} 