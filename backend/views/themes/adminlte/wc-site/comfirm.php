<?php

use \backend\assets\WeChatAsset;

$this->title = "兑奖";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];
$musicPath = $this->getAssetManager()->publish('@wechat/music/')[1];

?>
    <img src="<?=$imgPath?>/comfirm_1.png" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
    <div id="info" class="row" style="position: absolute;left:0;bottom: 0;width: 100%;text-align: center;height: 200px;">
        <p>奖品:<?=$prizeName;?></p>
        <p>兑奖码:<?=$winCode;?></p>
    </div>
<?php //没有中奖?>

<?php

?>
<script>
    var info = "<?php if (isset($err)){
    echo $err;
}else{
    echo "您已成功兑奖,兑奖奖品为".$prizeName;
}?>";
    alert(info,"温馨提示");
</script>
