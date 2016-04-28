<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];
$musicPath = $this->getAssetManager()->publish('@wechat/music/')[1];

?>
<div class="container">
    <div class="row">
<img src="<?=$imgPath?>/activity_home.jpg" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
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
<div class="panel panel-default " style="border-radius:20px;margin-top: 84%;max-height: 200px;">
    <div class="panel-heading" style="border-top-left-radius: 20px;border-top-right-radius:20px;background-color: #ffffff;border-bottom: 2px solid #127500;text-align: center;color: #127500;font-size:16px;font-weight:bold;font-family: '黑体'">恭喜您获得</div>
    <div class="panel-body">
        <div class="container_fluid">
            <div class="row">
                <div class="col-xs-6"><img class="pull-right" width="50%" height="50%" src="<?=$imgPath?>/qingdao.gif"/></div>
                <div class="col-xs-6 text-center" style="win-height:150px;text-align: center;font-size:18px;font-family: '黑体' "><?=$prizeName;?></div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="clear: both;">
    <div style="display: table;width: auto;margin: -15px  auto 0;"><span id='awardForMe' class="btn btn-primary" style="margin:0;padding:0;border:none;width:100px;height:40px; font-size:18px;line-height:40px;border-radius:20px;color:white;background-color: #DA7809">确认领奖</span></div>
</div>
<!--<div class="container n" style="" >-->
<!--    <div class="row" style="display:table;min-width: 200px;margin: 0 auto;">-->
<!--        <div class="col-xs-10 win_back">-->
<!--            <div class="container-fluid">-->
<!--                <div class="row">-->
<!--                    <div class="col-xs-12" style="border-bottom: 2px solid #127500;color: #127500;text-align: center;height: 30px;line-height: 30px;">-->
<!--                        恭喜您获得-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            <div class="container-fluid" >-->
<!--                <div class="row">-->
<!--                    <div class="col-xs-6"><img src="--><?//=$imgPath?><!--/qingdao.gif"/></div>-->
<!--                    <div class="col-xs-6 text-center" style="text-align: center;">--><?//=$prizeName;?><!--</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </div>-->
<!--    <div class="row" style="clear: both;margin-top: 10px;">-->
<!--        <div style="display: table;width: auto;margin:  10px  auto 0;"><span id='awardForMe' class="btn btn-primary" style="color:white;background-color: #DA7809">确认领奖</span></div>-->
<!--    </div>-->
<!--</div>-->

<!--    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 997;" src="--><?//=$imgPath."/win"?><!--.png">-->
<!--    <div style='width: 180px;height: 180px;position:absolute;left:25%;top:25%;text-align: center;z-index: 998;'>-->
<!--        <img width="100%" height="100%" style="" src="--><?//=$qrPath?><!--"/>-->
<!--    </div>-->
<!--    <button class="btn btn-primary" style="position:absolute;right:2;top:2;z-index: 999;" id="awardForMe">我要兑奖</button>-->
<!--    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="--><?//=$imgPath."/".$resultKeyword?><!--.png">-->
<!--    <img width="100%" height="100%" style="position:absolute;left:0;top:0;text-align: center;z-index: 998;" src="--><?//=$imgPath."/{$resultKeyword}_".$result?><!--.png">-->
<?php } ?>
    </div>
</div>
<script>
    var result = <?php if(!isset($result)||$result === -1){echo 0;}else{echo 1;}?>;
    var url = "<?= isset($qrPath)&&isset($winCode)?Yii::$app->urlManager->createAbsoluteUrl(['wc-site/qr-code','img'=>$qrPath,'winCode'=>$winCode]):'';?>";

    $("#awardForMe").click(function(){
        window.location.href = url;
    })

    $
</script>
<style>
    .win_back{
        /*width: 200px;*/
        /*height: 137px;*/
        background-color: white;
        border-radius: 5px;

    }
</style>
