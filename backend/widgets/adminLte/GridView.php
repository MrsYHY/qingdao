<?php
/**
 * User: yoyo
 * Date: 15-5-10
 * Time: 下午5:03
 */

namespace backend\widgets\adminLte;



class GridView extends \yii\grid\GridView{

    public $options = [];
    public $tableOptions = ['class' => 'table table-striped table-bordered table-hover dataTable no-footer" id="datatable_orders" aria-describedby="datatable_orders_info','role'=>'grid'];
    public $layout = "<div class=\"table-responsive\">{items}</div>\n<div class=\"row\"><div class=\"col-sm-5\">{summary}\n</div><div class='col-sm-7'>{pager}</div></div>";

    public $summaryOptions = ['class'=>'dataTables_info'];

    public function run()
    {
        $view = $this->getView();

        parent::run();
    }

}