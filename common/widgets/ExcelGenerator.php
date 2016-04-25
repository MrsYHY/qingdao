<?php
/**
 * 快速导出Excel类
 * @author panxiale
 */
namespace common\widgets;

use yii\base\Exception;

class ExcelGenerator extends Widget
{
    /**
     * @var array|Illuminate\Database\Query\Builder 数据
     */
    public $dataProvider;

    /**
     * 字段数据过滤函数
     */
    public $filterCallback;


    /************** 以下是excel文件配置参数 *****************/

    /**
     * @var array
     * 表格头array(名称=>字段名)
     */
    public $excelHead;

    /**
     * 生成的excel文件名(不包含.xls)
     */
    public $filename;

    /*********************** end **************************/

    /************** 以下是导出excel配置参数 *****************/

    /**
     * 一次读取数据进行处理的数量
     */
    public $pageSize = 5000;

    /**
     * 是否切割文件(数据超过65535行,暂时不支持)
     */
    public $isCutFile = false;



    /**
     * 初始化
     * author:panxiale
     */
    public function init(){
        if(!is_array($this->dataProvider))
            throw new Exception('ExcelGenerator 暂时不支持数组以外的数据');
        if($this->filename === null)
            $this->filename = date('Y-m-d').'_'.substr(md5(microtime()),0,10).rand(1000,9999);

    }


    /**
     * 开始执行
     * author:panxiale
     */
    public function run(){

        if($this->isCutFile)
            exit('未开发！');
        else
            $this->generateExcel();
        $this->end();
        return '';
    }

    /**
     * 生成excel
     * author:panxiale
     */
    protected function generateExcel()
    {
        $this->renderHttpHead();
        echo $this->getHead();
        while(($dataList = $this->getData())!=null) {
            $this->handleData($dataList);
        }
    }


    /**
     * 获取数据
     */
    private function getData ()
    {
        if($this->dataProvider === null)
            return null;
        $data = $this->dataProvider;
        $this->dataProvider = null;
        return $data;
    }

    /**
     * 转换数据为可读的数据
     * author:panxiale
     * @param array $data
     * @return array
     */
    private function handleData($data)
    {
        if($this->filterCallback != null) {
            foreach ($data as &$row) {
                $row = call_user_func($this->filterCallback, $row);
                echo $this->toExcelRow($row);
            }
        } else{
            foreach ($data as $row) {
                echo $this->toExcelRow($row);
            }
        }

    }

    /**
     * 生成http头部
     * author:panxiale
     * addTime:2014-4-8
     * @param string $filename
     */
    protected function renderHttpHead()
    {
        //禁止输出页面底部跟踪日志
        $_SERVER['HTTP_X_REQUESTED_WITH'] ='XMLHttpRequest';
        if($this->isCutFile){
            $filename = $this->filename.'.zip';
            header('Content-Description: File Transfer');
        }
        else{
            $filename = $this->filename.'.xls';
            header("Content-Type: application/vnd.ms-excel");
        }
        header("Content-Disposition:attachment;filename=" . $filename);

        header("Content-Type: charset=GBK");
        header("Content-type:application/octet-stream");
        //header("Accept-Ranges:bytes");

        header("Pragma: no-cache");
        header("Expires: 0");
    }

    /**
     * 获取excel头部
     * author:panxiale
     * addTime:2014-4-8
     */
    protected function getHead()
    {
        // 导出xls 开始
        $head = array();
        if (! empty($this->excelHead)) {
            foreach ($this->excelHead as  $v) {
                $head[] =  ' '.$v;
            }
            $head = implode("\t", $head);
            return iconv('UTF-8', 'GBK//IGNORE', $head)."\t\n";
        }
    }

    /**
     * 转换数据
     * author:panxiale
     * @param array $data
     * @return string
     */
    private function toExcelRow ($data = array() )
    {
        $row = implode("\t", $data)."\t\n";
        return iconv('UTF-8', 'GBK//IGNORE', $row);
    }

    /**
     * 导出excel结束
     * author:panxiale
     * addTime:2014-4-10
     */
    protected function end()
    {
//        if(!empty($this->backupDataBase)){
//            $modelName = $this->modelClassName;
//            $modelName::$db = $this->oldDbConnection;
//        }
    }
}