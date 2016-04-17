<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-20
 * Time: 下午4:13
 */

namespace common\models;

use common\activeRecords\BlogImages;
use common\forms\BlogImageForm;

class BlogImagesModel extends BaseModel{

    /**
     * 博客图片更新
     *
     * @author kouga-huang
     * @since 2015-12-20
     */
    public function BlogImageUpdate(BlogImageForm $blogImageForm){
        $blogImage = BlogImages::getById($blogImageForm->id);
        if(empty($blogImage)){
            $this->addErrors(array("获取不到该图片信息"));
            return false;
        }
        $blogImage->setAttributes($blogImageForm->getAttributes());
        if(!$blogImage->save()){
            $this->addErrors($blogImage->getErrors());
            return false;
        }
        return true;
    }

}