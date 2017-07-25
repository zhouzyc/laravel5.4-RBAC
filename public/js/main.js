/**
 * Created by fly on 16-4-23.
 */



$('.quitLogin').click(function () {
    var $this = $(this);
    $this.attr('disabled', 'disabled');
    var _token = $('#_token').val();

    $.ajax({
        url: '/Login/quit.html',// 跳转到 action
        data: {_token: _token},
        type: 'post',
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data['status'] == 1) {
                location.replace(location.href);
            } else {
                layer.msg(data['msg'], { icon: 2});
            }
            $this.attr('disabled', false);
        },
        error: function () {
            $this.attr('disabled', false);
        }
    });
    return false;
});


$('.lockScreen').click(function () {
    var $this = $(this);
    $this.attr('disabled', 'disabled');
    var _token = $('#_token').val();

    $.ajax({
        url: '/lock',// 跳转到 action
        data: {_token: _token},
        type: 'post',
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data['status'] == 1) {
                location.replace(location.href);
            } else {
                layer.msg(data['msg'], { icon: 2});
            }
            $this.attr('disabled', false);
        },
        error: function () {
            $this.attr('disabled', false);
        }
    });
    return false;
});

$('.del').click(function () {
    var $this = $(this);
    $this.attr('disabled', 'disabled');
    var id = $this.data('id');
    var uri = $this.data('uri');
    var _token = $('#_token').val();
    layer.confirm('你确定要删除吗？', {
        btn: ['确定', '取消'], //按钮
        skin: 'layui-layer-molv'
    }, function () {
        layer.closeAll();
        layer.load();
        $.post(uri, {id: id, _token: _token}, function (result) {
            if (result['status'] == 1) {
                location.replace(location.href);
            }
            layer.closeAll('loading');
            $this.attr('disabled', false);
        });
    }, function () {
        $this.attr('disabled', false);
    });

});

$('.status').change(function () {
    var $this = $(this);
    $this.attr('disabled', 'disabled');
    var id = $this.data('id');
    var name = $this.data('name');
    var status = $this.val();
    var uri = $this.data('uri');
    var _token = $('#_token').val();
    var param = {};
    param[name] = status;
    $.post(uri, {id:id,param:param,_token: _token}, function (result) {
        if (result['status'] == 1) {
            layer.msg('修改成功', { icon: 1});
        }else {
            layer.msg(result['msg'], { icon: 2});
        }
        $this.attr('disabled', false);
    });

});
/**
 * @param base64Codes
 *            图片的base64编码
 */
function sumitImageFile(base64Codes, obj, form) {

    var formData = new FormData(form);

    formData.append("file", convertBase64UrlToBlob(base64Codes));


    $.ajax({
        url: '/upload/img',
        type: "POST",
        data: formData,
        dataType: "json",
        processData: false,         // 告诉jQuery不要去处理发送的数据
        contentType: false,        // 告诉jQuery不要去设置Content-Type请求头

        success: function (data) {
            if (data['status'] == 1) {
                obj.val(data['data']['src']);
                layer.msg('剪裁成功', { icon: 1});
            } else {
                layer.msg(data['msg'], { icon: 2});
            }

        }
//                xhr:function(){            //在jquery函数中直接使用ajax的XMLHttpRequest对象
//                    var xhr = new XMLHttpRequest();
//
//                    xhr.upload.addEventListener("progress", function(evt){
//                        if (evt.lengthComputable) {
//                            var percentComplete = Math.round(evt.loaded * 100 / evt.total);
//                            console.log("正在提交."+percentComplete.toString() + '%');        //在控制台打印上传进度
//                        }
//                    }, false);
//
//                    return xhr;
//                }
    });
}
/**
 * 将以base64的图片url数据转换为Blob
 * @param urlData
 *            用url方式表示的base64图片数据
 */
function convertBase64UrlToBlob(urlData) {

    var bytes = window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte

    //处理异常,将ascii码小于0的转换为大于0
    var ab = new ArrayBuffer(bytes.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < bytes.length; i++) {
        ia[i] = bytes.charCodeAt(i);
    }

    return new Blob([ab], {type: 'image/png'});
}


$('.adminlogpage').on("click",".adminlog li",function () {
    var $this = $(this);
    $this.attr('disabled', 'disabled');
    var page = $this.data('page');
    var name = $this.data('name');
    var _token = $('#_token').val();
    $.ajax({
        url: '/upload/adminlog',
        data: {
            page: page,
            name: name,
            _token: _token
        },
        type: 'post',
        cache: false,
        dataType: 'html',
        success: function (data) {
            $('.adminlogpage').html($(data));
            $this.attr('disabled', false);
        },
        error: function () {
            $this.attr('disabled', false);
        }
    });

});

function getLocalTime(nS) {
    var curDate = new Date(parseInt(nS));
    var year = curDate.getFullYear();
    var month = lt9addPre0(curDate.getMonth() + 1);
    var day = lt9addPre0(curDate.getDate());
    var weekDay = curDate.getDay();
    var hour = lt9addPre0(curDate.getHours());
    var minute = lt9addPre0(curDate.getMinutes());
    var second = lt9addPre0(curDate.getSeconds());

    return year + '.' + month + '.' + day + ' ' + hour + ':' + minute + ':' + second;
}
function lt9addPre0(num){
    return num <= 9 ? "0"+num : num;
}
