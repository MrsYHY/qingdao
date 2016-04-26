<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];

?>
<img src="<?=$imgPath?>/activity_home.jpg" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">

<div id="info" class="row" >
    <button class="btn btn_guanzhu">即刻关注</button>
</div>

<style>
    #info{
        width:200px;
        height:150px;
        position: absolute;
        bottom: 5%;
        left: 50%;
        margin-left: -100px;
        background-image:url("<?=$imgPath?>/gz_back.png?>") ;
    }
    .btn_guanzhu{
        background-color: #DA7809;
        font-family: "黑体";
        font-size: 14px;
        color: white;
        margin: 100px auto 0;
    }
</style>

