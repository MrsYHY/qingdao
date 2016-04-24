<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午8:52
 */

namespace backend\controllers;


use backend\config\SystemConfig;
use backend\forms\WeChatForm;
use backend\services\WcSiteService;
use common\activeRecords\LuckDrawResult;
use common\activeRecords\Prizes;
use common\activeRecords\TerminalUser;
use common\components\Tool;
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

        $user = new TerminalUser();
        $user->terminal_user_token = Tool::randAbc(10).date("YmdHis",time());
        $user->role = TerminalUser::ROLE_XIAOFEI;
        $user->draw_luck_total = SystemConfig::LUCK_DRAW_TOTAL;
        $user->draw_luck_num = 0;
        $user->sign_in_num = -1;
        $user->last_luck_draw_time = time()-24*60*60;
        $user->save();

        $wechatForm->user_token = $user->terminal_user_token;
        if ($wechatForm->submitByApi()) {
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

        if ( $wechat->submitByApi() ){
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
                $luckResult = LuckDrawResult::findByPk($params['result']);
                if(empty($luckResult)){
                    $result = -1;
                }else{
                    if ($luckResult->result == LuckDrawResult::NOT_ZHONG){
                        $result = -1;
                    }else{
                        $prize = Prizes::find($luckResult->prize_id)->one();
                        if(empty($prize)){
                            $result = -1;
                        }else{
                            $resultKeyword = WcSiteService::$PRIZE_FOR_IMAGE[$prize->keyword];
                            $result = $luckResult->prize_level;//中奖等级
                            $qrPath = LuckDrawResult::generateQrCode($luckResult,$params['user_token']);
                        }
                    }
                }
            }
        }

        return $this->render('luck-draw-result',compact('noValidForUser','result','resultKeyword','qrPath'));

    }

    /**
     * 兑奖
     */
    public function actionComfirm(){
        $this->layout = 'weixin';
        $param = $this->request();
        $service = $this->getService();
        if (!isset($param ['result_id']) || !isset($param ['win_code'])) {
            $err = '参数有误';
            return $this->render('comfirm',compact('err'));
        }
        $result = $service->comfirm($param);
        if (!is_object($result)){
            $err = $result;
            return $this->render('comfirm',compact('err'));
        }
        $prizeName = Prizes::findByPk($result->prize_id)->name;
        return $this->render('comfirm',compact('err','prizeName'));
    }

    public function actionQrCode(){
        $this->layout = 'weixin';
        $img = $this->request('img');
        return $this->render('qr_code',compact('img'));
    }
    

} 