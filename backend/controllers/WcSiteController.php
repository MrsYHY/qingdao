<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-19
 * Time: 下午8:52
 */

namespace backend\controllers;


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
        return $this->render('index');
    }

    /**
     * 摇奖请求接口
     */
    public function actionLuckDraw(){
        $service = $this->getService();
        $params = $this->request();

        //判断是否关注

        //判断活动是否合法

        //判断设备id是否合法


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