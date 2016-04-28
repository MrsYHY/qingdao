<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];

?>
<img src="<?=$imgPath?>/activity_home.jpg" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<!--<div class="panel panel-default " style='border-radius:10px;width: 200px;height: 150px;'>-->
<!--    <div class="panel-body" style='background-image: url("--><?//=$imgPath?><!--/gz_back.png")'>-->
<!---->
<!--    </div>-->
<!--</div>-->
<div id="info"  >
<img width="100%" height="100%"style="position: relative;left:0;right0;z-index: -1" src="<?=$imgPath?>/gz_back.png?>">
    <div class="btn_guanzhu">
        <button class="btn"  id='guanzhu' style="font-size:20px; border-radius:10px;width:150px;height:60px;background-color: #DA7809;">即刻关注</button>
    </div>
</div>

<style>
    #info{padding: 0;margin: 0;
        width:400px;
        height:300px;
        position: absolute;
        bottom: 5%;
        left: 30%;
        margin-left: -100px;
        /*background-image:url("<?=$imgPath?>/gz_back.png?>") ;*/
    }
    .btn_guanzhu{
        display: table;width: auto;margin:  -80px  auto 0;
        font-family: "黑体";
        font-size: 14px;
        color: white;
    }
</style>

<script>
    var url = "<?=\common\config\BaseConfig::WECHAT_ACTIVITY_URL?>";
    $("#guanzhu").click(function(){
        window.location.href = url;
    });
</script>

