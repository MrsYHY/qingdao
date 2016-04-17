<?php
/**
 * @author kouga-huang
 * @since 15-12-8 下午2:18
 */
namespace common\forms;

class SysDictionaryForm extends  BaseForm{

    public $id;
    public $keyword;
    public $parent;
    public $name;
    public $parent2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword', 'parent','name'], 'safe'],
            [['keyword','parent','name'],'required','on'=>['create']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'keyword'=>'关键字',
            'name'=>'描述',
            'parent'=>'父字典',
        ];
    }

}