<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model backend\forms\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$url = $this->theme->getBaseUrl();
\backend\assets\AppAsset::register($this);
$this->registerCssFile($url.'/plugins/font-awesome/css/font-awesome.min.css');
$this->registerCssFile($url.'/plugins/bootstrap/css/bootstrap.min.css');
$this->registerCssFile($url.'/plugins/uniform/css/uniform.default.css');
$this->registerCssFile($url.'/css/pages/login-soft.css');
$this->registerCssFile($url.'/css/components-md.css');
$this->registerCssFile($url.'/css/plugins-md.css');
$this->registerCssFile($url.'/css/layout.css');
$this->registerCssFile($url.'/css/themes/default.css');
$this->registerCssFile($url.'/css/custom.css');

$this->registerJsFile($url.'/plugins/backstretch/jquery.backstretch.min.js',['depends'=>'yii\web\JqueryAsset']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html  lang="<?= Yii::$app->language ?>">
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>登陆系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="favicon.ico"/>
    <?php $this->head() ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-md login">
<?php $this->beginBody() ?>
<!-- BEGIN LOGO -->
<div class="logo">
    <a href="index.html">
        <img src="<?=$url?>/img/logo-big.png" alt=""/>
    </a>
</div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
<!-- BEGIN LOGIN FORM -->
  <?php $form = ActiveForm::begin()?>
    <h3 class="form-title">登陆你的账号</h3>
    <div class="alert alert-danger <?= $model->hasErrors()?'display-show':'display-hide'?>">
        <button class="close" data-close="alert"></button>
			<span><?= $model->hasErrors()?current($model->getFirstErrors()):''?> </span>
    </div>
    <?= $form->field($model,'username',['inputTemplate'=>'<div class="input-icon"><i class="fa fa-lock"></i>{input} </div>'])->label(null,['class'=>'visible-ie8 visible-ie9'])->error(['tag'=>'span'])?>
    <?= $form->field($model,'password',['inputTemplate'=>'<div class="input-icon"><i class="fa fa-user"></i>{input} </div>'])->passwordInput()->label(null,['class'=>'visible-ie8 visible-ie9'])->error(['tag'=>'span'])?>
    <div class="form-actions">
            <?= Html::activeCheckbox($model,'rememberMe')?>
        <button type="submit" class="btn blue pull-right">
            登陆 <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>
    <div class="forget-password" style="margin-top: 0px">
        <h4>忘记密码 ?</h4>
        <p>
            别担心, 点击 <a href="javascript:;" id="forget-password">这里 </a>重置你的密码.
        </p>
    </div>

<?php $form->end()?>
<!-- END LOGIN FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
    2015 &copy; yoyosys - cms .
</div>
<!-- END PAGE LEVEL SCRIPTS -->
<?php $this->endBody() ?>
<script>
    jQuery(document).ready(function() {
        $.backstretch([
            "<?=$url?>/img/bg/1.jpg",
            "<?=$url?>/img/bg/2.jpg",
            "<?=$url?>/img/bg/3.jpg",
            "<?=$url?>/img/bg/4.jpg"
        ], {
                fade: 1000,
                duration: 8000
            }
        );
    });
</script>
<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>