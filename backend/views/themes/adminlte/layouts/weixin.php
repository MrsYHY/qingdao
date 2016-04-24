<?php
use \backend\assets\WeChatAsset;

WeChatAsset::register($this);


$this->registerAssetBundle('yii\web\JqueryAsset',\yii\web\View::POS_HEAD);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head lang="cn">
    <meta name="viewport" content="width=640"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,target-densitydpi=high-dpi,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?=$this->title?></title>

    <?php $this->head() ?>

    <style type="text/css">

        .alert-danger{
            color: #fff !important;
            background: #030020 !important;
        }

    </style>

</head>
<?php $this->beginBody() ?>
<body class="container">
<section class="content">
    <?= $content?>
</section>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>

