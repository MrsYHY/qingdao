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


    const ONE_LEVEL_MESSAGE_QUEUE        = 'push_system:one_level:message_queue';//一级消息队列
    const TWO_LEVEL_MESSAGE_QUEUE        = 'push_system:two_level:message_queue';//二级消息队列
    const THREE_LEVEL_MESSAGE_QUEUE      = 'push_system:three_level:message_queue';//三级消息队列
    const FOUR_LEVEL_MESSAGE_QUEUE       = 'push_system:four_level:message_queue';//四级消息队列
    const FIVE_LEVEL_MESSAGE_QUEUE       = 'push_system:five_level:message_queue';//五级消息队列
    const DELAYED_MESSAGE_QUEUE          = 'push_system:delayed:message_queue';//延时推送消息队列
    const PRE_DELETE_MESSAGE_QUEUE       = 'push_system:pre_deleted:message_queue';//预删除消息队列
    const MESSAGE_SEND_POOL              = 'push_system:message_send_pool';//消息发送池
    const REQUEST_QUEUE_POOL             = 'push_system:request_queue_pool';//消息排队池

    /**
     * 是否需要软停止 当key所指向的值为1时表示要软停止
     */
    const RUAN_CLOSE_OK                  = 1;//确定要软停止
    const ONE_LEVEL_CLOSE                = 'push_system:close_queue:one_level';//一级软停止
    const TWO_LEVEL_CLOSE                = 'push_system:close_queue:two_level';//二级软停止
    const THREE_LEVEL_CLOSE              = 'push_system:close_queue:three_level';//三级软停止
    const FOUR_LEVEL_CLOSE               = 'push_system:close_queue:four_level';//四级软停止
    const FIVE_LEVEL_CLOSE               = 'push_system:close_queue:five_level';//五级软停止
    const DELAYED_CLOSE                  = 'push_system:close_queue:delayed';//推迟软停止
    const PRE_DELETE_CLOSE               = 'push_system:close_queue:pre_delete';//预删除软停止
    const PERSISTENT_CLOSE               = 'push_system:close_queue:persistent';//归档软停止
    const MONITOR_CLOSE                  = 'push_system:close_monitor_queue:monitor';//服务监控软停止

    //服务监控与队列进程的通信 key
    const ONE_LEVEL_MONITOR_QUEUE        = 'push_system:monitor_queue:one_level';//一级
    const TWO_LEVEL_MONITOR_QUEUE        = 'push_system:monitor_queue:two_level';//二级
    const THREE_LEVEL_MONITOR_QUEUE      = 'push_system:monitor_queue:three_level';//三级
    const FOUR_LEVEL_MONITOR_QUEUE       = 'push_system:monitor_queue:four_level';//四级
    const FIVE_LEVEL_MONITOR_QUEUE       = 'push_system:monitor_queue:five_level';//五级
    const DELAYED_MONITOR_QUEUE          = 'push_system:monitor_queue:delayed';//推迟
    const PRE_DELETE_MONITOR_QUEUE       = 'push_system:monitor_queue:predelete';//预删除
    const PERSISTENT_MONITOR_QUEUE       = 'push_system:monitor_queue:persistent';//归档

    static $messageQueueKeyAtRedis       = [
        1=>self::ONE_LEVEL_MESSAGE_QUEUE,
        2=>self::TWO_LEVEL_MESSAGE_QUEUE,
        3=>self::THREE_LEVEL_MESSAGE_QUEUE,
        4=>self::FOUR_LEVEL_MESSAGE_QUEUE,
        5=>self::FIVE_LEVEL_MESSAGE_QUEUE,
        'delayed'=>self::DELAYED_MESSAGE_QUEUE,
        'pre_deleted'=>self::PRE_DELETE_MESSAGE_QUEUE,
        'message_poll'=>self::MESSAGE_SEND_POOL,
    ];

    static $closeKeyAtRedis             = [
        1=>self::ONE_LEVEL_CLOSE,
        2=>self::TWO_LEVEL_CLOSE,
        3=>self::THREE_LEVEL_CLOSE,
        4=>self::FOUR_LEVEL_CLOSE,
        5=>self::FIVE_LEVEL_CLOSE,
        'delayed'=>self::DELAYED_CLOSE,
        'pre_deleted'=>self::PRE_DELETE_CLOSE,
        'persistent'=>self::PERSISTENT_CLOSE,
    ];

    /**
     * 消息队列状态机
     * @var array
     */
    static $messageQueueStateMachine = [
        self::ONE_LEVEL_MESSAGE_QUEUE => ['next'=>self::DELAYED_MESSAGE_QUEUE,'limit'=>BaseConfig::ONE_LEVEL_BOTTLENECK],//一级
        self::TWO_LEVEL_MESSAGE_QUEUE => ['next'=>self::DELAYED_MESSAGE_QUEUE,'limit'=>BaseConfig::TWO_LEVEL_BOTTLENECT],//二级
        self::THREE_LEVEL_MESSAGE_QUEUE=>['next'=>self::DELAYED_MESSAGE_QUEUE,'limit'=>BaseConfig::TWO_LEVEL_BOTTLENECT],//三级
        self::FOUR_LEVEL_MESSAGE_QUEUE=>['next'=>self::DELAYED_MESSAGE_QUEUE,'limit'=>BaseConfig::FOUR_LEVEL_BOTTLENECT],//四级
        self::FIVE_LEVEL_MESSAGE_QUEUE=>['next'=>self::DELAYED_MESSAGE_QUEUE,'limit'=>BaseConfig::FIVE_LEVEL_BOTTLENECT],//五级
        self::DELAYED_MESSAGE_QUEUE=>['next'=>self::PRE_DELETE_MESSAGE_QUEUE,'limit'=>BaseConfig::DELAYED_LEVEL_BOTTLENECT],//延时
        self::PRE_DELETE_MESSAGE_QUEUE=>['next'=>false,'limit'=>BaseConfig::PRE_DELETE_LEVEL_BOTTLENECT],//预删除
    ];

    public function getMessageQueueLevel($level){
        if($level === 'delayed'){
            $level = -1;
        }elseif($level === 'pre_deleted'){
            $level = -2;
        }
        switch($level){
            case 1:
                return "1级消息队列";
                break;
            case 2:
                return "2级消息队列";
                break;
            case 3:
                return "3级消息队列";
                break;
            case 4:
                return "4级消息队列";
                break;
            case 5:
                return "5级消息队列";
                break;
            case -1:
                return "延时发送消息队列";
                break;
            case -2:
                return "预删除消息队列";
            break;
            case -3:
                return "";
                break;
            default:
                return "未知消息队列级别";
        }
    }

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
    public function outputJson($params){
        ob_clean();
        header('Content-type: text/html; charset=utf8');
        echo Json::encode($params);
        exit;
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
 