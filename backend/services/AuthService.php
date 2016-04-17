<?php
/**
 * @author kouga-huang
 * @since 15-12-2 下午3:53
 */

namespace backend\services;

use common\services\BaseService;
use yii\data\ActiveDataProvider;

class AuthService extends BaseService{

    /**
     * 查看权限
     *
     * @author kouga-huang
     * @since 2015-12-3
     */
    public function view($authItem){
        $dataProvider = new ActiveDataProvider([
            'query' => $authItem->getAuthItemChildren(),
        ]);
        return $dataProvider;
    }
}