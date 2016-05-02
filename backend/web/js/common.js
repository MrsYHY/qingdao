/**
 * Created by kouga-huang
 */

(function ($){
    $.fn.checkBoxAjax = function(){
        $(this).find("input[type='checkbox']").each(function(){
            $(this).click(function(){
                var isAjaxSubmit = $(this).data("ajax-submit") == 'undefined' ? false : $(this).data("ajax-submit");
                var method = $(this).data("submit-method") == 'undefined' ? "POST" : $(this).data("submit-method");
                var params = $(this).data("params") == 'undefined' ? {} : $(this).data("params");
                params.isSelect = $(this).is(":checked") ? "true" : "false";
                var ajaxUrl = $(this).data("ajax-url") == 'undefined' ? "" : $(this).data("ajax-url");
                if(isAjaxSubmit == true){
                    $.ajax({
                        type : method,
                        url : ajaxUrl,
                        dataType : "json",
                        data : params,
                        success : function(data){console.log(data);
                          if(data.hasOwnProperty("code")){
                              if(data.code == 1){
                                  showToast("操作成功",!data.hasOwnProperty("message") ? "操作成功" : data.message.message,"success");
                              }else{
                                  showToast("操作失败",!data.hasOwnProperty("message") ? "操作失败" : data.message.message,"error");
                              }
                          }
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown){
                            showToast("操作失败","status:" + XMLHttpRequest.status + ",readyState:" + XMLHttpRequest.readyState + ",textStatus:" + textStatus ,"error");
                        }
                    });
                }
                return true;
            });
        });
    }
})(window.jQuery);

function showToast(title,message,type){
    var date = new Date();
    divId = "message" + date.getTime();
    div = '<div class="modal fade" id="' + divId +  '" tabindex="-1" role="dialog">';
    div += '<div class="modal-dialog ' + 'modal-' + type + '">';
    div += '<div class="modal-content">';
    div += '<div class="modal-header">';
    div += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    div += '<h4 class="modal-title">' + title + '</h4>';
    div += '</div>';
    div += '<div class="modal-body">'
    div += '<p>' + message + '</p>';
    div += '</div>';
    div += '<div class="modal-footer">';
    div += '<button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>';
    div += '</div>';
    div += '</div>';
    div += '</div>';
    div += '</div>';
    $("body").append(div);
    $("#"+divId).modal({'show':true});
}

