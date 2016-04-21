<?php

use \backend\assets\WeChatAsset;

$this->title = "首页";

WeChatAsset::register($this);

$imgPath = $this->getAssetManager()->publish('@wechat/img/')[1];
$musicPath = $this->getAssetManager()->publish('@wechat/music/')[1];

?>
<img src="<?=$imgPath?>/activity_home.jpg" width="100%" height="100%" style="position:absolute; left:0; top: 0; z-index: -1;">
<div id="info" class="row" style="margin-top:20px;height: 50px">

</div>
<div class="row" style="text-align: center; padding-top: 40%;z-index: 999">
    <div class="span1">
        &nbsp;
    </div>
    <div class="span10">
        <img id='shake' style="width: 40%;" src="<?=$imgPath?>/rock.png">
    </div>
    <div class="span1">
        &nbsp;
    </div>
</div>

<!-- 玩法说明 -->
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
    $(document).ready(function(){
        var myAudioWin = new Audio("<?=$musicPath?>/music/rock.mp3");

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

                if (speed > SHAKE_THRESHOLD) {
                    // TODO:在此处可以实现摇一摇之后所要进行的数据逻辑操作
                    $("#info").html('');
                    shake('shake');
                    myAudioWin.play();//播放
                    setTimeout(function(){
                        $.ajax({
                            url:'service.php',
                            type:"POST",
                            data:{action:'rock'},
                            success:function(data){
                                if(data==1){
                                    window.location.href='commit.html';
                                }else if(data==-1){
                                    $("#info").html('<div class="alert alert-danger col-xs-10 col-xs-offset-1"><h4>抱歉，现在不是抽奖时间。</h4></div>');
                                    setTimeout(function(){$("#info").css({display:"none"})},1000);
                                }else{
                                    $("#info").html('<div class="alert alert-danger col-xs-10 col-xs-offset-1"><h4>很遗憾, 您没有摇到奖品, 再试一次吧?</h4></div>');
                                    setTimeout(function(){$("#info").css({display:"none"})},1000);
                                }
                            }
                        })


                    },500)
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
                $panel.animate({left:box_left-(40-10*i)},50);
                $panel.animate({left:box_left+2*(40-10*i)},50);
            }
        }


    });
</script>

