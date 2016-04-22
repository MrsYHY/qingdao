<?php
/**
 * @author kouga-huang
 * @since 15-12-2 下午3:42
 */
namespace common\services;

use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Json;

use common\config\BaseConfig;
use common\components\Tool;

class BaseService extends Model{


    protected $redis;

    public function openConnection(){
        if(empty($this->redis)){
            $this->redis = \Yii::$app->redis;
        }
        return $this->redis;
    }

    protected  function zAddToRedis($key,$member,$score=1){
        $redis = $this->openConnection();
        try{
            $result = BaseService::tryOperator(function()use($redis,$key,$member,$score){
                $maxScore = $redis->zrevrange($key,0,0,'withscores');
                if(!empty($maxScore)){
                    $score = bcadd($maxScore[1],1);
                }
                $result = $redis->zadd($key,$score,$member);
                return $result;
            });
        }catch (Exception $e){
            $err = $e->getMessage();
            empty($err) ? $err = '未知错误' : $err = Tool::toUTF8($err);
            $this->addErrors([$err]);
            return false;
        }
        return $result;
    }

    protected  function sAddToRedis($key,$member){
        $redis = $this->openConnection();
        try{
            $result = BaseService::tryOperator(function()use($redis,$key,$member){
                $result = $redis->sadd($key,$member);
                return $result;
            });
        }catch (Exception $e){
            $err = $e->getMessage();
            empty($err) ? $err = '未知错误' : $err = Tool::toUTF8($err);
            $this->addErrors([$err]);
            return false;
        }
        return $result;
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
     * 重试机制
     * 当回调函数返回false代表操作失败，需要进行重试
     *
     * @author kouga-huang
     * @since 2015-12-6
     * @param $callback
     * @return bool
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public static function tryOperator($callback){
        $result = false;
        try{
            $i = 0;
            while($i < 3){
                $result = $callback();
                if($result !== false){
                    return $result;
                }
                $i++;
            }
        }catch (Exception $e){
            throw $e;
        }
        return $result;
    }

}
 