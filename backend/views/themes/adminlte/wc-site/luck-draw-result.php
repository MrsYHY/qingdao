<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];
$musicPath = $this->getAssetManager()->publish('@wechat/music/')[1];

?>
<img src="<?=$imgPath?>/comfirm_1.png" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<div id="info" class="row" style="margin-top:40px;height: 50px">
    <?php if($noValidForUser === true){?>
        <div class="alert alert-danger col-xs-10 col-xs-offset-1"><h4>您还未关注我们微信公共号！</h4></div>
    <?php }?>
</div>
<?php //没有中奖
if(!isset($result)||$result === -1){
    $no = [2,3,4,5,6,7,8,9,10];
    shuffle($no);$first = $no[0];
    ?>
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 997;" src="<?=$imgPath."/no_win"?>.png">
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="<?=$imgPath."/no_win_".$first?>.png">
<?php }else{?>
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 997;" src="<?=$imgPath."/win"?>.png">
    <div style='width: 180px;height: 180px;margin: 20% auto 0;background-image: url("<?=$qrPath?>")'>
<!--        <img width="120px" height="120px" style="position:absolute;left:25%;top:25%;text-align: center;z-index: 998;" src="--><?//=$qrPath?><!--"/>-->
    </div>
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="<?=$imgPath."/".$resultKeyword?>.png">
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="<?=$imgPath."/{$resultKeyword}_".$result?>.png">
<?php } ?>