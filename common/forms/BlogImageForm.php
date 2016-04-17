<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-18
 * Time: 下午10:40
 */

namespace common\forms;

class BlogImageForm extends BaseForm{
    public $id;
    public $belong_to;
    public $image_url;
    public $name;

    public function rules()
    {
        return [
            [['name','belong_to'],'required','on'=>'upload'],
            [['image_url'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg','on'=>'upload'],
            [['id','name','belong_to','image_url'],'required','on'=>'update'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'博客图片ID',
            'image_url'=>'博客图片',
            'name'=>'博客图片名字',
            'belong_to'=>'博客图片归属'
        ];
    }

}