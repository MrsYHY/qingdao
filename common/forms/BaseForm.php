<?php
/**
 *
 * @author ImKouga
 * Date: 15-11-5
 * Time: 下午9:24
 */
namespace common\forms;

use Yii;
use yii\base\Model;


class BaseForm extends Model{


    /**
     * 获取get post数据
     *
     * @author ImKouga
     * @since 2015-11-5
     * @return boolean
     */
    public function submit($name = null, $defaultValue = null){
        $request = Yii::$app->request->post($name,$defaultValue);
        if(empty($request)){
            $request = Yii::$app->request->get($name,$defaultValue);
        }
        if (empty($request)) {
            return false;
        }
        return $this->load($request);
    }

    public function submitByApi($name = null, $defaultValue = null){
        $request = Yii::$app->request->post($name,$defaultValue);
        if(empty($request)){
            $request = Yii::$app->request->get($name,$defaultValue);
        }
        if (empty($request)) {
            return false;
        }
        return $this->load($request,'');
    }



}