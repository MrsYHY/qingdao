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


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'pageComment'=>'数据示例'
        ];
    }



} 