<?php

namespace common\activeRecords;

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
class Menu extends BaseActiveRecord
{
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
            [['desc'], 'string'],
            [['display', 'parent', 'index'], 'integer'],
            [['route'], 'string', 'max' => 80],
            [['name'], 'string', 'max' => 20],
            [['icon'], 'string', 'max' => 50],
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
            'index' => '排序',
        ];
    }

    public function getChilden()
    {
        return $this->hasMany(Menu::className(),['parent'=>'id'])->from(Menu::tableName().' childen');
    }
}
