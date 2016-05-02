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
?>
<?php $this->beginPage() ?>
<!DOCTYPE html  lang="<?= Yii::$app->language ?>">
<html lang="zh-cn">
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
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>青岛啤酒</b>抽奖</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="alert alert-danger <?= $model->hasErrors()?'show':'hide'?>">
            <button class="close" data-close="alert"></button>
            <span><?= $model->hasErrors()?current($model->getFirstErrors()):''?> </span>
        </div>
        <span><?= $model->hasErrors()?current($model->getFirstErrors()):''?> </span>
        <?php $form = ActiveForm::begin()?>
        <?= $form->field($model,'username',['inputTemplate'=>'<div class="input-icon"><i class="fa fa-lock"></i>{input} </div>'])->label(null,['class'=>'visible-ie8 visible-ie9'])->error(['tag'=>'span'])?>
        <?= $form->field($model,'password',['inputTemplate'=>'<div class="input-icon"><i class="fa fa-user"></i>{input} </div>'])->passwordInput()->label(null,['class'=>'visible-ie8 visible-ie9'])->error(['tag'=>'span'])?>
        <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> 记住密码
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
                <!-- /.col -->
            </div>
        <?php $form->end()?>

        <div class="social-auth-links text-center hide">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
                Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
                Google+</a>
        </div>
        <!-- /.social-auth-links -->

        <a href="#">忘记密码</a><br>
        <a href="register.html" class="text-center hide">Register a new membership</a>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<
<?php $this->endBody();?>
</body>
<html>
<?php $this->endPage() ?>