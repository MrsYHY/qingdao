<?php
/**
 * Created by PhpStorm.
 * User: ImKouga
 * Date: 15-11-5
 * Time: 下午9:47
 */
namespace common\controller;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class BaseController extends Controller{

    protected $service;

    /**
     * 获取GET POST数据
     *
     * @author ImKouga
     * @since 2015-11-5
     */
    protected  function request($name = null,$defaultValue = null){
        $request = Yii::$app->request->post($name,$defaultValue);
        if(empty($request)){
            $request = Yii::$app->request->get($name,$defaultValue);
        }
        return $request;
    }

    /**
     * 输出json
     *
     * @author kouga-huang
     * @since 2015-12-6
     * @param $params
     */
    protected  function outputJson($message,$code){
        ob_clean();
        $obj = new \stdClass();
        $obj->code = $code;
        $obj->message = $message;
        header('Content-type: text/json; charset=utf8');
        echo Json::encode($obj);
        exit;
    }

    protected function successByJson($message,$code=1){
        $this->outputJson($message,$code);
    }
    protected function failByJson($message,$code=0){
        $this->outputJson($message,$code);
    }

    /**
     * $param string|array
     */
    protected function createUrl($param){
        $url = \Yii::$app->urlManager->createUrl($param);
        return $url;
    }
}