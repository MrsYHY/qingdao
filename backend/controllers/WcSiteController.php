<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午8:52
 */

namespace backend\controllers;


use backend\forms\WeChatForm;
use backend\services\WcSiteService;
use common\activeRecords\LuckDrawResult;
use common\activeRecords\Prizes;
use common\activeRecords\TerminalUser;
use common\controller\BaseController;

class WcSiteController extends BaseController{

    protected $service;
    public function getService(){
        if ( $this->service instanceof WcSiteService ){
            return $this->service;
        }
        $this->service = new WcSiteService();
        return $this->service;
    }

    public function actionWeChat(){

    }

    public function actionIndex(){
        $this->layout = 'weixin';
        $wechatForm = new WeChatForm();
        $wechatForm->setScenario('luck_draw_page');
        if ($wechatForm->submit()) {
            if (!$wechatForm->validate()) {

            }
        }
        return $this->render('index',compact('wechatForm'));
    }

    /**
     * 摇奖请求接口
     * @params user_token 用户token
     * @params activity_id 活动id
     * @params device_id 设备id
     */
    public function actionLuckDraw(){
        $service = $this->getService();

        $wechat = new WeChatForm();
        $wechat->setScenario('luck_draw_request');

        if ( $wechat->submit() ){
            if ( $wechat->validate() ){
               $service->luckDraw( $wechat );
            } else {
                $errors = $wechat->getFirstErrors();
                $firstError = reset($errors);
                empty($firstError) ? $firstError = "未知错误" : '';
                $this->failByJson($firstError);
            }
        }
        $this->failByJson("没有传递相应的参数给我");
    }

    /**
     * 摇奖结果页
     * @params user_token 用户的token
     */
    public function actionLuckDrawResult(){
        $this->layout = 'weixin';
        $service = $this->getService();
        $params = $this->request();

        $noValidForUser = false;
        if ( empty($params['user_token']) ){
            $noValidForUser = true;
        }else{
            $user = TerminalUser::getByTerminalUserToken($params['user_token']);
            if ($user === false){
                $noValidForUser = true;
            }else{
                $luckResult = LuckDrawResult::getNotAwardByUserId($user->id);
                if( empty($luckResult) ){
                    $result = -1;
                }else{
                    $prize = Prizes::find($luckResult->prize_id)->one();
                    $resultKeyword = WcSiteService::$PRIZE_FOR_IMAGE[$prize->keyword];
                    $result = $luckResult->prize_level;//中奖等级
                }
            }
        }

        return $this->render('luck-draw-result',compact('noValidForUser','result','resultKeyword'));

    }
    

} 