<?php

namespace backend\forms;

use common\forms\BaseForm;
use Yii;


/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $route
 * @property string $name
 * @property string $desc
 * @property integer $display
 * @property integer $parent
 * @property integer $index
 */
class MenuForm extends BaseForm
{
    public $id;
    public $route;
    public $name;
    public $desc;
    public $icon;
    public $display = 1;
    public $parent=0;
    public $parent2;
    public $index = 50;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc','icon'], 'string'],
            [['display', 'parent','parent2', 'index'], 'integer'],
            [['route'], 'string', 'max' => 80],
            [['name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route' => '菜单地址',
            'name' => '菜单名',
            'desc' => '菜单描述',
            'display' => '显示',
            'icon' => '图标',
            'parent' => '父菜单',
            'parent2' => '父菜单',
            'index' => '排序',
        ];
    }
}
