<?php
/**
 * User: yoyo
 * Date: 15-5-16
 * Time: 下午7:27
 */

namespace backend\components;


use yii\base\Model;

class JsonModel extends Model{

    public $status = 'UNKNOW';

    public $message;

    public $data;

    public $redirect = false;

    const SUCCESS = 'SUCCESS';

    const FAIL = 'FAIL';

    const UNKNOW = 'UNKNOW';


    public static function success($message,$data = '',$redirect = true)
    {
        $json  = new JsonModel();
        $json->status = self::SUCCESS;
        $json->message = $message;
        $json->data = $data;
        $json->redirect = $redirect;
        if($redirect){
            \Yii::$app->session->setFlash('notify',['message-manage'=>$message,'type'=>'success','title'=>'操作提示']);
        }
        return $json;
    }

    public static function fail($message,$redirect = false){

        $json  = new JsonModel();
        $json->status = self::FAIL;
        $json->message = $message;
        $json->redirect = $redirect;
        if($redirect){
            \Yii::$app->session->setFlash('notify',['message-manage'=>$message,'type'=>'success','title'=>'操作提示']);
        }
        return $json;
    }

    function __toString()
    {
        return json_encode($this);
    }


} 