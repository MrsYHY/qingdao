<?php
/**
 * @author panxiale
 * create at: 2015/9/16 13:34
 */

namespace common\widgets;

abstract class Widget {

    /**
     * @var 递增项
     */
    public static $count = 1;

    /**
     * @var string 部件唯一ID
     */
    private $_id;

    public function __construct($config = [])
    {
        foreach($config as $k=>$v){
            $this->$k = $v;
        }
        $this->init();
    }

    /**
     * 初始化
     */
    protected function init()
    {

    }

    /**
     * 生成html
     * @return string
     */
    public function run(){

    }

    /**
     * 创建部件
     * @param $config
     * @return mixed
     */
    public static function create($config = []){
        $class = get_called_class();
        return new $class($config);
    }

    /**
     * @return string 部件唯一ID
     */
    public function getId(){
        if($this->_id)
            return $this->_id;
        return $this->_id = 'w'.++self::$count;
    }


    public function __toString()
    {
        try {
            return $this->run();
        }catch (Exception $e){
            if(Config::get('app.debug')){
                if(ob_get_contents())
                    ob_clean();
                echo '<pre>';
                echo 'message: '.$e->getMessage().'<br />';
                echo $e->getTraceAsString();
                exit;
            }else
                return '控件初始化失败';
        }
    }


}