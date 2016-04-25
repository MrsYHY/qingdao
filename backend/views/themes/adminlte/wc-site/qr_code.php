<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];
$musicPath = $this->getAssetManager()->publish('@wechat/music/')[1];

?>
<img src="<?=$imgPath?>/comfirm_2.png" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<img src="<?=$imgPath?>/comfirm_3.png" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<div id="info" class="row" style="margin-top:20px;height: 50px">
    <img style="position: absolute;top: 20%;left: 25%;" src="<?=$img?>">
</div>

<div class="row" style="text-align: center;   position: absolute; bottom: 17%; margin-bottom: 10px; left: 50%; margin-left: -100px; z-index: 998;">
    <div class="span1">
        &nbsp;
    </div>
    <div class="span10">
        <!--        <img style="width: 45%;" src="--><?//=$imgPath?><!--/title.png">-->
    </div>
    <div class="span1">
        &nbsp;
    </div>
</div>
