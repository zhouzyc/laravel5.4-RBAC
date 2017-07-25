
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />


    <title>{{$title}}</title>

    <link href="/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <script src="/inspinia/js/jquery-2.1.1.js"></script>
    <script src="/inspinia/js/bootstrap.min.js"></script>
    <link href="/inspinia/css/animate.css" rel="stylesheet">
    <link href="/inspinia/css/style.css" rel="stylesheet">
    <script src="/org/layer/layer/layer.js"></script>
    <script src="/org/laydate/laydate/laydate.js"></script>
    <script src="/org/layui/layui/layui.js"></script>

</head>

<body class="gray-bg" style="background: url(/img/bg.jpg) no-repeat;background-size: 100% 100%">

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div style="background-color: rgba(238, 238, 238, 0.3);padding: 20px 20px;border-radius:20px ">
        <div>

            <h1 class="logo-name">DOC</h1>

        </div>
        <h3>欢迎使用文档系统</h3>
        <form class="m-t" role="form">
            <div class="form-group">
                <input type="text" class="form-control account" placeholder="用户名" required="" />
            </div>
            <div class="form-group">
                <input type="password" class="form-control password" placeholder="密码" required="" />
            </div>
            <div class="form-group">
                <input style="width: 120px;float: left;height: 40px;" type="text" class="form-control logincode" placeholder="验证码" />
                <a onclick="javascript:re_captcha();" style="float: right;margin-bottom: 10px;" >
                    <img src="{!! URL('code/1') !!}" alt="验证码" title="刷新图片" width="100" height="40" id="{{ csrf_token()}}" border="0">
                </a>
            </div>
            <button type="button" class="btn btn-primary block full-width m-b login">Login</button>

            <a class="forgot-pass"><small>忘记密码?</small></a>
<!--            <p class="text-muted text-center"><small>Do not have an account?</small></p>-->
<!--            <a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>-->
            <input id="_token" type="hidden" name="_token" value="{{ csrf_token()}}"/>
        </form>
        <p class="m-t"> <small>Copyright &copy; 2016</small> </p>
    </div>
</div>

<?php $reg_type = env('REG_TYPE','mail') ?>

<!-- Mainly scripts -->

<script src="/js/main.js"></script>
<script src="/js/md5.js"></script>
</body>
<script>
    function re_captcha() {
        $url = "{{ URL('code') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('{{ csrf_token()}}').src=$url;
    }
    $(".m-t").bind("keydown",function(e){
        // 兼容FF和IE和Opera
        var theEvent = e || window.event;
        var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
        if (code == 13) {
            //回车执行查询
            $(".btn-primary").click();
        }
    });


    $('.forgot-pass').click(function(){

        var url = "/Login/forgotpass.html";
        var area = ['450px', '350px'];
        var reg_type = '{{$reg_type}}';

         if(reg_type == 'mail'){
             url = "/Login/forgotpassmail.html";
             area = ['450px', '160px'];
         }
        //iframe层-父子操作
        layer.open({
            type: 2,
            title: '找回密码',
            area: area,
            fix: false, //不固定
            maxmin: true,
            content: url
        });
    });


    function parentRefresh(){
        window.location.reload(true);
    }
    $('.login').click(function () {
        var $this = $(this);
        var password = $('.password').val();
        var account = $('.account').val();
        var logincode = $('.logincode').val();
//    var remember = $('#remember').get(0).checked;

        var _token = $('#_token').val();
        if (account == '') {
            layer.tips('账号不能为空', '.account', { tips: 3});
            return false;
        }

        if (password == '') {
            layer.tips('密码不能为空', '.password', { tips: 3});
            return false;
        }
        if (logincode == '') {
            layer.tips('验证码不能为空', '.logincode', { tips: 3});
            return false;
        }

        $this.attr('disabled', 'disabled');
        $.ajax({
            url: '/Login/login.html',// 跳转到 action
            data: {
                password: (password),
                account: account,
                _token: _token,
                logincode: logincode
//            remember:remember
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data['status'] == 1) {
                    location.replace('/Index/index.html');
                } else {
                    layer.msg(data['msg'], { icon: 2});
                    re_captcha();
                    $('.logincode').val('');
                }
                $this.attr('disabled', false);
            },
            error: function () {
                $this.attr('disabled', false);
            }
        });
    });
</script>
</html>
