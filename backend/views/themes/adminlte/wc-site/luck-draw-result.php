<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];
$musicPath = $this->getAssetManager()->publish('@wechat/music/')[1];

?>
<img src="<?=$imgPath?>/comfirm_1.png" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<?php if($noValidForUser === true){?>
<div id="info" class="row" style="margin-top:40px;height: 50px">
        <div class="alert alert-danger col-xs-10 col-xs-offset-1"><h4>您还未关注我们微信公共号！</h4></div>
</div>
<?php }?>
<?php //没有中奖
if(!isset($result)||$result === -1){
    $no = [2,3,4,5,6,7,8,9,10];
    shuffle($no);$first = $no[0];
    ?>
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 997;" src="<?=$imgPath."/no_win"?>.png">
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="<?=$imgPath."/no_win_".$first?>.png">
<?php }else{?>
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 997;" src="<?=$imgPath."/win"?>.png">
<!--    <div style='width: 180px;height: 180px;position:absolute;left:25%;top:25%;text-align: center;z-index: 998;'>-->
<!--        <img width="100%" height="100%" style="" src="--><?//=$qrPath?><!--"/>-->
<!--    </div>-->
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="<?=$imgPath."/".$resultKeyword?>.png">
    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="<?=$imgPath."/{$resultKeyword}_".$result?>.png">
<?php } ?>

<script>
    var result = <?php if(!isset($result)||$result === -1){echo 0;}else{echo 1;}?>;
    $(document).ready(function(){
        if (result == 1){
        setTimeout(function(){
            window.location.href = "<?= isset($qrPath)?Yii::$app->urlManager->createAbsoluteUrl(['wc-site/qr-code','img'=>$qrPath]):'';?>";
        },3000);
        }
    })
</script>