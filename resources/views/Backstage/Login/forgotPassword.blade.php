
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{$title}}</title>

    <link href="/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">


    <link href="/inspinia/css/animate.css" rel="stylesheet">
    <link href="/inspinia/css/style.css" rel="stylesheet">
    <link href="/inspinia/css/plugins/steps/jquery.steps.css" rel="stylesheet">

    <script src="/inspinia/js/jquery-2.1.1.js"></script>
    <script src="/inspinia/js/bootstrap.min.js"></script>


    <script src="/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <script src="/inspinia/js/plugins/staps/jquery.steps.min.js"></script>


    <script src="/org/layer/layer/layer.js"></script>
    <script src="/org/laydate/laydate/laydate.js"></script>
    <script src="/org/layui/layui/layui.js"></script>

</head>

<body class="gray-bg">
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <form id="form" action="#" class="wizard-big wizard clearfix" role="application" novalidate="novalidate">
                    <div class="steps clearfix">
                        <ul role="tablist">
                            <li role="tab" id="form-t-0" class="first current" aria-disabled="false" aria-selected="true">
                                <a >
                                    <span class="number">1.</span> 验证手机
                                </a>
                            </li>
                            <li role="tab" id="form-t-1" class="done" aria-disabled="true">
                                <a >
                                    <span class="number">2.</span> 设置密码
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div style="margin: 0 20px;">
                        <fieldset id="form-p-0" role="tabpanel" aria-labelledby="form-h-0" class="body current" aria-hidden="false">
                            <div class="row">
                                <div style="width: 150px;float: left;margin-top: 10px;margin-left: 17px;">
                                    <input id="phone"  placeholder="手机号" type="text" class="form-control required" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div style="width: 260px;float: left;margin-top: 10px;margin-left: 17px;">
                                    <input  style="width: 150px;float: left" type="text" class="form-control " id="code" placeholder="验证码">
                                    <a onclick="javascript:re_captcha1();" style="float: right;margin-bottom: 10px;">
                                        <img  style="height: 34px;" src="{!! URL('code/1') !!}" alt="验证码" title="刷新图片" width="100" height="40" id="{{ csrf_token()}}1" border="0">
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div style="width: 150px;float: left;margin-top: 10px;margin-left: 17px;">
                                    <input id="yphone"  placeholder="短信验证码" type="text" class="form-control required" value="">
                                </div>
                                <button type="button" style="margin: 10px;float: left" class="btn btn-warning  btn-sm searchbtn send-code" >发送验证码</button>
                            </div>
                            <div class="actions clearfix">
                                <button type="button" style="float: right" class="btn btn-success btn-sm phone-next" >下一步</button>
                            </div>
                        </fieldset>

                        <fieldset id="form-p-1" role="tabpanel" aria-labelledby="form-h-1" class="body" aria-hidden="true" style="display: none;">
                            <div class="row">
                                <div style="width: 150px;float: left;margin-top: 10px;margin-left: 17px;">
                                    <input id="pass"  placeholder="密码" type="password" class="form-control required" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div style="width: 150px;float: left;margin-top: 10px;margin-left: 17px;">
                                    <input id="cpass"  placeholder="确定密码" type="password" class="form-control required" value="">
                                </div>
                            </div>

                            <div class="actions clearfix">
                                <button type="button" style="float: right" class="btn btn-success btn-sm phone-ok" >完成</button>
                            </div>
                        </fieldset>

                    </div>
                    <input id="_token" type="hidden" name="_token" value="{{ csrf_token()}}"/>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

</html>
<script src="/js/main.js"></script>
<script src="/js/md5.js"></script>
<script>
    function re_captcha1() {
        $url = "{{ URL('code') }}";
        $url = $url + "/" + Math.random();
        var token = $('#_token').val();
        document.getElementById(token+'1').src=$url;
    }
    var time=59;
    function timess ($btn){
        var timeoutvar = setInterval(function ($btn){
            if(time>0) {
                time--;
                $('.send-code').html(time+'秒后重新发送');
            }else{
                $('.send-code').html('重新发送');
                $('.send-code').attr('disabled', false);
                clearInterval(timeoutvar);
            }
        },1000);
    }


    $(document).on('click','.send-code',function(){

        var phone = $('#phone').val();

        var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
        if((!phone || !reg.test(phone))){
            layer.tips('手机号格式错误', '#phone',{ tips: 3});
            return false;
        }

        var code = $('#code').val();
        if(!code ){
            layer.tips('请输入验证码', '#code',{ tips: 3});
            return false;
        }

        var $this = $(this);
        $this.attr('disabled', 'disabled');
        time = 59;

        var _token = $('#_token').val();
        timess($this);
        $.post("/send/sms.html", {phone:phone,code:code,type:1,_token: _token}, function (result) {
            if (result['status'] == 1) {
                layer.msg('发送成功', { icon: 1});

            }else {
                layer.msg(result['msg'], { icon: 2});
            }
            $this.attr('disabled', false);

        });
    });
    $(document).on('click','.phone-next',function(){
        var yphone = $('#yphone').val();
        if(!yphone){
            layer.msg('请输入验证码', { icon: 2});
            return false;
        }
        var form0 = $('#form-p-0');
        var formt0 = $('#form-t-0');
        form0.removeClass('current');
        form0.css('display','none');

        formt0.removeClass('current');
        formt0.addClass('done');

        var form1 = $('#form-p-1');
        var formt1 = $('#form-t-1');
        form1.addClass('current');
        form1.css('display','block');

        formt1.removeClass('done');
        formt1.addClass('current');


    });
    $(document).on('click','.phone-ok',function(){
        var yphone = $('#yphone').val();
        if(!yphone){
            layer.msg('请输入验证码', { icon: 2});
            return false;
        }
        var phone = $('#phone').val();
        if(!phone){
            layer.msg('手机号码不正确', { icon: 2});
            return false;
        }
        var pass = $('#pass').val();
        var cpass = $('#cpass').val();
        if(!pass ){
            layer.msg('请输入密码', { icon: 2});
            return false;
        }
        if(pass != cpass ){
            layer.msg('两次密码不相同', { icon: 2});
            return false;
        }

        var $this = $(this);
        $this.attr('disabled', 'disabled');

        var _token = $('#_token').val();
        $.ajax({
            url: '/Login/forgotpass.html',
            data: {
                pass: (pass),
                cpass: (cpass),
                phone: phone,
                code:yphone,
                _token: _token
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                if(data['status'] == 1){
                    layer.msg('修改成功', { icon: 1});
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    window.parent.parentRefresh();
                    parent.layer.close(index);

                }else {
                    layer.msg(data['msg'], { icon: 2});
                }
                $this.attr('disabled', false);
            },
            error: function () {
                $this.attr('disabled', false);
            }
        });


    });
</script>