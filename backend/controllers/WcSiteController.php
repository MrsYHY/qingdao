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
use callmez\wechat\sdk\mp\ShakeAround;
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

        $ticket = $this->request('ticket');
        if (empty($ticket)){
            $openId = Tool::randAbc(10).date("YmdHis",time());
        }else{
            $shakeWechat = new ShakeAround(\Yii::$app->mp_wechat);
            $data = $shakeWechat->getUserShakeInfo(['ticket'=>$ticket]);
            if (empty($data)){
                $openId = Tool::randAbc(10).date("YmdHis",time());
            }else if(isset($data->openid)) {
                $openId = $data->openid;
            }else{
                $openId = Tool::randAbc(10).date("YmdHis",time());
            }
        }

        $_user = TerminalUser::getByTerminalUserToken($openId);
        if (empty($_user)) {
            $user = new TerminalUser();
            $user->terminal_user_token = $openId;
            $user->role = TerminalUser::ROLE_XIAOFEI;
            $user->draw_luck_total = SystemConfig::LUCK_DRAW_TOTAL;
            $user->draw_luck_num = 0;
            $user->sign_in_num = -1;
            $user->last_luck_draw_time = time()-24*60*60;
            $user->save();
        }

        $wechatForm->user_token = $openId;
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
        set_time_limit(0);
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
                        $prize = Prizes::findByPk($luckResult->prize_id);
                        if(empty($prize)){
                            $result = -1;
                        }else{
                            $result = $luckResult->prize_level;//中奖等级
                            $prizeName = $prize->name;
                            $qrPath = LuckDrawResult::generateQrCode($luckResult,$params['user_token']);
                            $winCode = $luckResult->win_code;
                        }
                    }
                }
            }
        }

        return $this->render('luck-draw-result',compact('prize','noValidForUser','result','qrPath','winCode','prizeName'));

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
        $winCode = $result->win_code;
        return $this->render('comfirm',compact('err','prizeName','winCode'));
    }

    public function actionQrCode(){
        $this->layout = 'weixin';
        $img = $this->request('img');
        $winCode = $this->request('winCode');
        return $this->render('qr_code',compact('img','winCode'));
    }

    public function actionGuanzhu(){
        $this->layout = "weixin";
        return $this->render('guanzhu');
    }

} 