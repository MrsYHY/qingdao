<?php
/**
 * User: yoyo
 * Date: 15-5-23
 * Time: 上午8:45
 */

namespace backend\components;


use common\models\Assignment;
use common\models\Itemchild;
use yii\base\Behavior;
use yii\web\ForbiddenHttpException;
use Yii;

class AccessControl extends Behavior{

    public $allow=[];

    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        $this->owner = $owner;
        $owner->on(Controller::EVENT_BEFORE_ACTION, [$this, 'beforeFilter']);
    }

    public function beforeFilter()
    {
        $route = \Yii::$app->controller->getRoute();
        if(!in_array($route,$this->allow)){
            if(Yii::$app->user->getIsGuest())
                Yii::$app->user->loginRequired();
            if(! Yii::$app->user->can($route))
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return true;
    }


} 