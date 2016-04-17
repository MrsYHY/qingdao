<html>
<?php
$this->title = '控制面板';
$this->params['breadcrumbs'] = [$this->title];
?>

<div id="text" class="push_system_have_read">

</div>
<?php
echo file_get_contents('http://local.push.com/queue/js-sdk-for-push-system.html')?>
</html>
<script>
    function kk(r){
        me = JSON.parse(r);
        if(me.code == 1){
            var div = window.document.getElementById('text');
            div.innerHTML = me.message.message + "--" + me.message.to_where + "--" +  me.message.to_who + "--" +  me.message.to_action;
            div.setAttribute('data-where',me.message.to_where);
            div.setAttribute('data-who',me.message.to_who);
            div.setAttribute('data-action',me.message.to_action);
        }
    }
    var push = new push_system();
    push.getPushMessage('45','w','d',{responseCallback:kk});
</script>