<?php
/**
 * User: yoyo
 * Date: 15-5-24
 * Time: 下午10:41
 */

namespace backend\widgets\metronic;



use common\models\Menu;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class MainMenu extends Widget{

    public $items;

    public $options = [
        'class'=>'page-sidebar-menu',
        'data-keep-expanded'=>'false',
        'data-auto-scroll'=>"true" ,
        'data-slide-speed'=>"200",
        'id'=>'main_menu'
    ];
    public function init()
    {

    }

    private function initMenuChild(&$items){
        foreach($items as $k=>$v){
            $childMenus = Menu::find()->select(['id','route as url','name as label','display as visible','icon'])->where('display = 1 and parent = '.$v['id'])->orderBy('index asc')->asArray()->all();
            $this->initMenuChild($childMenus);
            $items[$k]['items'] = $childMenus;
            if(!\Yii::$app->user->can($v['url']) && $v['url']!='javascript:;'){
                unset($items[$k]);
                continue;
            }
            if(count( $childMenus )== 0 && $v['url']=='javascript:;')
                unset($items[$k]);
        }

    }

    public function renderItem($itemList,$isChild = false)
    {
        if($isChild)
            $options = ArrayHelper::merge($this->options,['class'=>'sub-menu']);
        else
            $options = $this->options;
        $itemStr = Html::beginTag('ul',$options);
        foreach($itemList as $item){
            $label = '<span class="title">'.$item['label'].'</span>';
            if(isset($item['icon']))
                $label='<i class="'.$item['icon'].'"></i>'.$label;
            $options = ArrayHelper::getValue($item, 'options', []);
            $items = ArrayHelper::getValue($item, 'items');
            $url = ArrayHelper::getValue($item, 'url', '#');
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

    //        if (isset($item['active'])) {
    //            $active = ArrayHelper::remove($item, 'active', false);
    //        } else {
    //            $active = $this->isItemActive($item);
    //        }

            if ($items != null) {
                $linkOptions['data-toggle'] = 'dropdown';
                Html::addCssClass($options, 'dropdown ');
                Html::addCssClass($linkOptions, 'dropdown-toggle');
                $label .= ' ' . Html::tag('span', '', ['class' => 'arrow']);
                if (is_array($items)) {
    //                if ($this->activateItems) {
    //                    $items = $this->isChildActive($items, $active);
    //                }
                    $childItems = $this->renderItem($items,true);
                }
            }else
                $childItems = '';

    //        if ($this->activateItems && $active) {
    //            Html::addCssClass($options, 'active');
    //        }
            $itemStr .=  Html::tag('li', Html::a($label, [$url], $linkOptions) . $childItems, $options);
        }
        $itemStr.=Html::endTag('ul');
        return $itemStr;
    }



    public function run()
    {
        $this->items = Menu::find()->select(['id','route as url','name as label','display as visible','icon'])->where('parent = 0 and display = 1')->orderBy('index asc')->asArray()->all();
        //$this->items[0]['options']=['class'=>'start'];
        $this->initMenuChild( $this->items );
        $menu = '<div class="page-sidebar md-shadow-z-2-i navbar-collapse collapse">';
        $menu .= $this->renderItem($this->items);
        $menu .= '</div>';
        echo $menu;
    }
}
/*
<div class="page-sidebar md-shadow-z-2-i navbar-collapse collapse">
<ul class="page-sidebar-menu " id="main_menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
<li class="start ">
    <a href="index.html">
        <i class="icon-home"></i>
        <span class="title">控制面板</span>
    </a>
</li>
    <li>
        <a href="javascript:;">
            <i class="icon-settings"></i>
            <span class="title">系统设置</span>
            <span class="arrow open"></span>
        </a>
        <ul class="sub-menu">
            <li>
                <a href="<?= \yii\helpers\Url::toRoute('auth/index')?>">权限管理</a>
            </li>
            <li>
                <a href="<?= \yii\helpers\Url::toRoute('menu/index')?>">菜单管理</a>
            </li>
        </ul>
    </li>
</ul>
<!-- END SIDEBAR MENU -->
</div>
*/