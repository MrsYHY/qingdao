<?php
/**
 * @author kouga-huang
 * @since 15-12-9 下午1:55
 */
namespace common\forms;


class BlogForm extends BaseForm{

    public $id;
    public $title;//标题
    public $subtitle;//子标题
    public $content;//内容
    public $author;//作者
    public $click;//点击量
    public $classification;
    public $classification2;
    public $state;//博客状态

    public function rules()
    {
        return [
            [['title','content','classification','state'],'required','on'=>['create']],
            [['title','content','classification','state'],'required','on'=>['update']],
            [['id','title','content','classification','classification2','author','click','state','subtitle'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'博客ID',
            'title'=>'标题',
            'content'=>'内容',
            'author'=>'作者',
            'click'=>'点击量',
            'classification'=>'文章分类',
            'state'=>'博客状态',
            'subtitle'=>'子标题'
        ];
    }


}
 