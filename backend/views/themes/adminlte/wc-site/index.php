<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];
$musicPath = $this->getAssetManager()->publish('@wechat/music/')[1];

?>
<script type="text/javascript"
        src="http://zb.weixin.qq.com/nearbycgi/addcontact/BeaconAddContactJsBridge.js">
</script>
<script type="text/javascript">
    BeaconAddContactJsBridge.ready(function(){
        //判断是否关注
        BeaconAddContactJsBridge.invoke('checkAddContactStatus',{} ,function(apiResult){
            if(apiResult.err_code == 0){
                var status = apiResult.data;
                if(status == 1){
                }else{
                    alert('您还没有关注我们哦！');
                    //跳转到关注页
                    BeaconAddContactJsBridge.invoke('jumpAddContact');
                }
            }else{
                alert(apiResult.err_msg)
            }
        });
    });
</script>
<img src="<?=$imgPath?>/activity_home.jpg" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<img src="<?=$imgPath?>/shake_home.png" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<img src="<?=$imgPath?>/shake_1.png" width="83px" height="40%" id="shake" style="clear: both;display: block;margin:61% auto 0; ">

<div id="info" class="row" style="margin-top:20px;height: 50px">

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
<script>
    var user_token = <?='"'.$wechatForm->user_token.'"'?>;
    var activity_id = <?='"'.$wechatForm->activity_id.'"'?>;
    var device_id = <?='"'.$wechatForm->device_id.'"'?>;
    $(document).ready(function(){
        var myAudioWin = new Audio("<?=$musicPath?>/rock.mp3");
        var localStorage = window.localStorage;
<!--        if (localStorage.getItem("first") == null || localStorage.getItem("first") == undefined){-->
<!--            localStorage.setItem("first",1);-->
<!--            window.location.href = "--><?//=Yii::$app->urlManager->createAbsoluteUrl(['wc-site/guanzhu'])?><!--";-->
<!--        }-->

//        var keyForStorage = 'openId';//localStorage.clear(keyForStorage)
//        localStorage.setItem(keyForStorage,user_token);
//        alert(localStorage.getItem(keyForStorage));

        if (window.DeviceMotionEvent) {
            // 移动浏览器支持运动传感事件
            window.addEventListener('devicemotion', deviceMotionHandler, false);
        }

        var SHAKE_THRESHOLD = 3000;
        // 定义一个变量保存上次更新的时间
        var last_update = 0;
        // 紧接着定义x、y、z记录三个轴的数据以及上一次出发的时间
        var x;
        var y;
        var z;
        var last_x;
        var last_y;
        var last_z;
        var count = 0;
        var canRequest = true;
        function deviceMotionHandler(eventData) {
            // 获取含重力的加速度
            var acceleration = eventData.accelerationIncludingGravity;

            // 获取当前时间
            var curTime = new Date().getTime();
            var diffTime = curTime -last_update;
            // 固定时间段
            if (diffTime > 100) {
                last_update = curTime;

                x = acceleration.x;
                y = acceleration.y;
                z = acceleration.z;

                var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 30000;

                if (speed > SHAKE_THRESHOLD && canRequest == true) {
//                    shake('shake');
                    canRequest = false;
                    // TODO:在此处可以实现摇一摇之后所要进行的数据逻辑操作
                    $("#info").html('');
                    myAudioWin.play();//播放
                    $.ajax({
                        async:true,
                        url:<?='"'.Yii::$app->urlManager->createAbsoluteUrl(['wc-site/luck-draw']).'"'?>,
                        type:"GET",
                        data:{user_token:user_token,activity_id:activity_id,device_id:device_id,open_id:user_token},
                        dataType:'json',
                        success:function(data){
                            canRequest = true;
                            if (data.code == 1) {
                                var luckDrawResultUrl = <?='"'.Yii::$app->urlManager->createAbsoluteUrl(['wc-site/luck-draw-result']).'"'?>;
                                res_user_token = data.message.user_token;
                                res_activity_id = data.message.activity_id;
                                res_device_id = data.message.device_id;
                                res_result = data.message.result;
                                var params = "?user_token=" + res_user_token + "&result=" + res_result;
                                window.location.href = luckDrawResultUrl + params;
                            }else{
//                                alert(data.message,'温馨提示');
                                $("#info").html('<div class="alert alert-danger col-xs-10 col-xs-offset-1"><h4>' + data.message + '</h4></div>');
//                                $("#info").css({display:"block"});
                                setTimeout(function(){$("#info").html('')},1000);
                            }
                         //   canRequest = true;
                        }
                    })
                }
                last_x = x;
                last_y = y;
                last_z = z;
            }
        }


        function shake(o){
            var $panel = $("#"+o);
            box_left = ($(window).width() -  $panel.width()) / 2;
            $panel.css({'left': box_left,'position':'absolute'});
            for(var i=1; 4>=i; i++){
                $panel.animate({left:box_left-(40-10*i)},150);
                $panel.animate({left:box_left+2*(40-10*i)},150);
            }
        }

        setInterval(function(){shake('shake')},500);
    });
</script>

