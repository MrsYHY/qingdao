<?php
/**
 * 重写摇一摇 依赖
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-23
 * Time: 上午11:33
 */

namespace common\rewritingDependency;


use callmez\wechat\sdk\mp\ShakeAround;

class ReWrite_ShakeAround extends ShakeAround{

    /**
     * 上传图片素材
     * @param $mediaPath
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function addMaterial($mediaPath)
    {
        $result = $this->wechat->httpPost(self::WECHAT_MATERIAL_ADD_PREFIX, [
            'media' => $this->wechat->uploadFile($mediaPath),
        ], [
            'access_token' => $this->wechat->getAccessToken(),
            'type'  => 'icon',
        ]);
        return isset($result['errcode']) && !$result['errcode'] ? $result['data'] : false;
    }

} 