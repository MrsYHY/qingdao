<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-22
 * Time: 下午10:11
 */

namespace backend\services;


use backend\forms\DeviceForm;
use callmez\wechat\sdk\mp\ShakeAround;
use common\activeRecords\WechatData;
use common\rewritingDependency\ReWrite_ShakeAround;
use common\services\BaseService;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class DevicesService extends BaseService{

    /**
     * @param null|array $pageIds
     * @return array
     */
    public function pageList($pageIds = null){
        if ($pageIds === null){
            $datas = WechatData::getByKeyword(WeChatService::SHAKE_PAGE);
            if (empty($datas)){
                return [];
            }
            $datas = ArrayHelper::map($datas,'id','wechat_data');
            $pageId = '[';
            foreach ($datas as $d) {
                $pageId .= unserialize($d) . ',';
            }
            $pageId = rtrim($pageId,',')."]";
        }else{
            $pageId = "[".implode(',',$pageIds)."]";
        }

        $shakeWechat = new ShakeAround(\Yii::$app->mp_wechat);

        $param ['type'] = 1;
        $param ['page_ids'] = $pageId;
        $result = $shakeWechat->searchPage($param);
        if ( $result === false) {
            return [];
        }
        $pages = [];
        if ( isset($result->pages) ){
            foreach ($result->pages as $_p) {
                $pages [] = get_class_vars($_p);
            }
            return $pages;
        }
        return [];
    }

    public function pageDelete($pageid){
        $params ['page_id'] = $pageid;
        $shakeWechat = new ShakeAround(\Yii::$app->mp_wechat);
        $result = $shakeWechat->deletePage($params);
        if ($result === false){
            throw new NotFoundHttpException('删除页面失败');
        }
        $search ['keyword'] = WeChatService::SHAKE_PAGE;
        $search ['wechat_data'] = serialize($pageid);
        WechatData::deleteBySome($search);
        return true;

    }

    public function pageUpdate(DeviceForm $deviceForm) {
        $params ['title'] = $deviceForm->pageTitle;
        $params ['description'] = $deviceForm->pageDescription;
        $params ['page_url'] = $deviceForm->pagePageUrl;
        $params ['comment'] = $deviceForm->pageComment;
        $params ['icon_url'] = $deviceForm->pageIconUrl;
        $shakeWechat = new ShakeAround(\Yii::$app->mp_wechat);
        $result = $shakeWechat->updatePage($params);
        if ($result === false){
            $this->addErrors(['修改页面失败']);
            return false;
        }
        return true;

    }
    /**
     * @param DeviceForm $deviceForm
     * @return bool|strimg 新增成功返回page_id 失败返回false
     *
     */
    public function pageCreate(DeviceForm $deviceForm){

        $shakeWechat = new ReWrite_ShakeAround(\Yii::$app->rewrite_mp_wechat);

        //上传图片素材
        $params = [];
        $params ['media'] = $deviceForm->pageIconUrl;
        $params ['type'] = 'icon';
        $result = $shakeWechat->addMaterial($deviceForm->pageIconUrl);
        if ($result === false){
            $this->addErrors(['上传图片素材到微信平台失败']);
            return false;
        }

        //新增页面
        $params = [];
        $params ['title'] = $deviceForm->pageTitle;
        $params ['description'] = $deviceForm->pageDescription;
        $params ['page_url'] = $deviceForm->pagePageUrl;
        $params ['comment'] = $deviceForm->pageComment;
        $params ['icon_url'] = isset($result->pic_url) ? $result->pic_url : '';

        $result = $shakeWechat->addPage($params);
        if ($result === false){
            $this->addErrors(['添加页面失败']);
            return false;
        }
        $search ['keyword'] = WeChatService::SHAKE_PAGE;
        $search ['wechat_data'] = isset($result->page_id) ? $result->page_id : '';

        $addResult = WechatData::addData($search);
        if ($addResult === true){
            return $result->page_id;
        }else{
            $this->addErrors($addResult);
            return false;
        }
    }

    /**
     * 查看我们自己有没有持久化 该页面id
     * @param $pageid
     */
    public function getPageIdBySelf($pageid){
        $search ['wechat_data'] = serialize($pageid);
        $search ['keyword'] = WeChatService::SHAKE_PAGE;
        $result = WechatData::serarch($search);
        if (empty($result)) {
            return false;
        }
        return $result;
    }

} 