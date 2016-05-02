<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-8
 * Time: 下午8:45
 */

namespace common\models;

use common\activeRecords\BlogDictionary;
use common\forms\BlogDictionaryForm;

class BlogDictionaryModel extends BaseModel{

    public function createBlogDictionary(BlogDictionaryForm $formModel){
        $blogDictionary = new BlogDictionary();
        $blogDictionary->setAttributes($formModel->getAttributes());
        $blogDictionary->keyword = strtoupper($blogDictionary->keyword);
        if(!$blogDictionary->save()){
            $this->addErrors($blogDictionary->getErrors());
            return false;
        }
        return true;
    }

    public function updateBlogDictionary(BlogDictionaryForm $formModel){
        $blogDictionary = BlogDictionary::getById($formModel->id);
        if(empty($blogDictionary)){
            $this->addErrors(['找不到此博客字典']);
            return false;
        }
        $blogDictionary->setAttributes($blogDictionary->getAttributes());
        if(!$blogDictionary->save()){
            $this->addErrors($blogDictionary->getErrors());
            return false;
        }
        return true;

    }


}