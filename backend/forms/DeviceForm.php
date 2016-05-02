<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-4-22
 * Time: 下午10:02
 */

namespace backend\forms;


use common\forms\BaseForm;

class DeviceForm extends BaseForm{

    //摇一摇页面
    public $pageTitle;
    public $pageDescription;
    public $pagePageUrl;
    public $pageComment;//数据示例
    public $pageIconUrl;//图标示例
    public $pageIconUpload;

    public $sale_name;
    public $device_keyword;
    public $user_id;
    public $shake_num;
    public $device_name;
    public $device_id;
    public $zone_id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_name','user_id','shake_num','device_id'],'safe'],
            [['device_keyword','device_name','zone_id'],'required','on'=>['createDevice','updateDevice']],
            [['pageIconUpload'],'file'],
            [['pageComment','pageIconUrl'],'safe'],
            [['pageTitle','pageDescription','pagePageUrl'], 'required','on'=>['pageCreate','pageUpdate']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           'pageTitle'=>'页面标题',
            'pageDescription'=>'页面描述',
            'pagePageUrl'=>'页面链接',
            'pageIconUrl'=>'图标链接',
            'pageComment'=>'数据示例',
            'sale_name'=>'促销员',
            'shake_num'=>'摇一摇次数',
            'device_keyword'=>'设备标识',
            'device_name'=>'设备名称',
            'zone_id'=>'所在大区',
        ];
    }



} 