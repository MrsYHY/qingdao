<?php
/**
 * @author kouga-huang
 * @since 15-12-2 下午3:47
 */
namespace common\activeRecords;

use yii\db\ActiveRecord;

class BaseActiveRecord extends ActiveRecord{

    /**
     * 将对象转化为数组
     * @param array $object
     * @return array|void
     */
    public static function objectToArray($object){
        if(!is_object($object)||!is_array($object)){
            return array();
        }
        if($object instanceof ActiveRecord){
            return $object->getAttributes();
        }
        $arr = array();
        foreach($object as $o){
            $arr [] = $o instanceof ActiveRecord ? $o->getAttributes() : array();
        }
        return $arr;
    }
}
 