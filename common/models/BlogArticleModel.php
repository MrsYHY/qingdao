<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-9
 * Time: 下午9:10
 */

namespace common\models;

use common\activeRecords\BlogArticle;
use common\forms\BlogForm;

class BlogArticleModel extends BaseModel{

    /**
     * 创建博客
     *
     * @author kouga-huang
     * @since 2015-12-8
     * @param $blogForm BlogForm
     */
    public function createBlog(BlogForm $blogForm){
        $blogArticle = new BlogArticle();
        $blogArticle->setAttributes($blogForm->getAttributes());
        $blogArticle->author = \Yii::$app->user->id;
        if(!empty($blogForm->classification2)){
            $blogArticle->classification = $blogForm->classification2;
        }
        if(!$blogArticle->save()){
            $this->addErrors($blogArticle->getErrors());
            return false;
        }
        return true;
    }

    /**
     * 更新博客
     *
     * @author kouga-huang
     * @since 2015-12-8
     * @param $blogForm BlogForm
     */
    public function updateBlog(BlogForm $blogForm){
        if(empty($blogForm->id)){
            $this->addErrors(array("该文章不存在，不允许修改"));
            return false;
        }
        $blogArticle = BlogArticle::getById($blogForm->id);
        $blogArticle->setAttributes($blogForm->getAttributes());
        if(!empty($blogForm->classification2)){
            $blogArticle->classification = $blogForm->classification2;
        }
        if(!$blogArticle->save()){
            $this->addErrors($blogArticle->getErrors());
            return false;
        }
        return true;
    }

}