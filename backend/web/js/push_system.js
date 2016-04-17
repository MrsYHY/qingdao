
/**
 * @author kouga-huang
 * @since 2016-04-08
 * @params to_where 推送方
 * @params to_who 推送给谁
 * @params to_action 推送动作
 * @params Object options 其他参数 该参数需要提供响应的回调函数
 */
function push_system(){

    var _cometForGetPushMessage = null;
    var _cometForUpdatePushMessage = null;

    /**
     * 获取推送消息
     * @param to_where
     * @param to_who
     * @param to_action
     * @param options
     */
     this.getPushMessage = function(to_where,to_who,to_action,options){
        var locationAt = 'push_system:' + new Date().getTime() + ":" + Math.random();//标识该请求方的位置  对于web，每个页面就是一个位置 防止重复推送
        url = PUSH_SYSTEM_DOMAIN + PUSH_SYSTEM_REQUEST_ACTION;
        options.locationAt = locationAt;
        _cometForGetPushMessage = new comet(url,to_where,to_who,to_action,options);
    }

    /**
     * 关闭长轮询
     */
    this.close = function(){
        if(_cometForGetPushMessage){
            _cometForGetPushMessage.close();
        }
    }

    /**
     * 标记消息为已读
     * @param to_where
     * @param to_who
     * @param to_action
     * @param options
     */
    this.updateMessageToHaveRead = function(to_where,to_who,to_action,options){
        url = PUSH_SYSTEM_DOMAIN + PUSH_SYSTEM_UPDATE_HAVE_READ;
        _cometForUpdatePushMessage = new comet(to_where,to_who,to_action,options);
    }

}


///////////////////////////////////////////////////////////////

function comet(url1,where,who,action,options1){
    var url = url1;
    var to_where = where;
    var to_who = who;
    var to_action = action;
    var locationAt = null;
    var options = options1;
    if(options.locationAt != undefined ){
        locationAt = options.locationAt;
    }
	var xhr;
	var self=this;
	var charsReceived = 0;
	var type=null;
	var data="";
	var eventName = "message";
	var lastEventId="";
	var retrydelay = 1000;
	var aborted = false;

	_init();
    _connect();
	_reconnect();
	
	function _init(){
		//创建一个XHR对象
		xhr = _createXHR();
		if(xhr === null){
			alert("创建xhr对象失败");
			return;
		}
		xhr.onreadystatechange = function(){
			switch(xhr.readyState){
				case 3: _processData(); break;
				case 4: _reconnect();break;
			}
		};
	}

    this.close = function(){
        aborted = true;
    }

	function _connect(){
		//通过connect()创建一个长期存在的连接 这里的代码展示了如何建立一个连接
        var params = "to_where=" + to_where + "&to_who=" + to_who + "&to_action=" + to_action;
        params +=  (typeof locationAt) != undefined ? "&location=" + locationAt : '';
		charsReceived = 0;
		type = null;
		xhr.open("GET",url + "?" + params);
		if(lastEventId){
			xhr.setRequestHeader("Last-Event-ID",lastEventId);
		}
		xhr.send();
	}
	
	function _reconnect(){
		//如果连接正常关闭，等待1秒钟再尝试连接
		if(aborted) {//在终止之后不再重连接
            console.log("已经正常被关闭连接");
			return;
		}
		if(xhr.status>=300){//报错之后不再重连接
			console.log('服务端出了意外，http返回码为' + xhr.status);
            return;
		}
		setTimeout(_connect,retrydelay);//1秒后
	}
	
	function _processData(){
		options.responseCallback(xhr.responseText);
	}

}

function _createXHR(){
    if (typeof  XMLHttpRequest != "undefined"){
        return new XMLHttpRequest();
    }else if(typeof ActiveXObject != "undefined"){
        if (typeof arguments.callee.activeXString != "string") {
            var versions = [ "MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHttp" ];
            for ( var i = 0, len = versions.length; i < len; i++) {
                try {
                    var xhr = new ActiveXObject(versions[i]);
                    arguments.callee.activeXString = versions[i];
                    return xhr;
                } catch (error) {
                    // TODO
                }
            }
        }else{
            return new ActiveXObject(arguments.callee.activeXString);
        }
        return null;
    }else{
        return null;
    }
}

///////////////////////对接推送系统的已读接口////////////////////////////////////////////

    function operatorForHaveRead(element){
        var where = element.getAttribute('data-where');
        var who = element.getAttribute('data-who');
        var action = element.getAttribute('data-action');
        var xhr = _createXHR();
        if( xhr === null ){
            alert("实例化xhr实例失败");
            return ;
        }
        var params = 'to_where=' + where + "&to_who=" + who + "&to_action=" + action;
        xhr.open('get',PUSH_SYSTEM_DOMAIN + PUSH_SYSTEM_UPDATE_HAVE_READ + "?" + params);
        xhr.onreadystatechange = function(){
            switch(xhr.readyState){
                case 3: console.log(xhr.responseText);break;
                case 4: console.log(xhr.responseText);break;
            }
        };
        xhr.send();
    }

    var pushMessages = document.getElementsByClassName('push_system_have_read');
    for ( var i = 0,length = pushMessages.length; i < length; i ++ ){
        var push = pushMessages[i];
        if( window.addEventListener ){//非ie
            push.addEventListener ( 'click',function(ev){operatorForHaveRead(ev.target)});
        } else{//ie
            push.attachEvent( 'onclick', function(ev){operatorForHaveRead(ev.target)});
        }
    }








