
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php  echo ($title);  ?></title>

    <link href="/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/inspinia/css/animate.css" rel="stylesheet">
    <link href="/inspinia/css/style.css" rel="stylesheet">

    <script src="/inspinia/js/jquery-2.1.1.js"></script>
    <script src="/inspinia/js/bootstrap.min.js"></script>
    <script src="/org/layer/layer/layer.js"></script>
    <script src="/org/laydate/laydate/laydate.js"></script>
    <script src="/org/layui/layui/layui.js"></script>
    <script src="/js/md5.js"></script>
</head>

<body class="gray-bg">

{{--<div class="lock-word animated fadeInDown">--}}
{{--<span class="first-word">LOCKED</span><span>SCREEN</span>--}}
{{--</div>--}}
<div class="middle-box text-center lockscreen animated fadeInDown">
    <div class="from">

        <input id="_token" type="hidden" name="_token" value="<?php  echo csrf_token();  ?>"/>

        <input type="hidden" name="token" id="token" value="{{ $token }}">



        <div class="form-group">

            <input type="password"  id="pass"  class="form-control" placeholder="输入新密码" required="">

        </div>
        <div class="form-group">
            <input type="password"  id="cpass"  class="form-control" placeholder="确认密码" required="">
        </div>

        <button type="button" class="btn btn-primary block full-width" id="send">重置密码</button>
    </div>
</div>


</body>

</html>
<script>
    $(function(){
        $('#send').click(function(){
            var $this = $(this);
            var pass = $('#pass').val();
            var cpass = $('#cpass').val();
            var token = $('#token').val();
            var _token = $('#_token').val();
            if(pass == '' ){
                layer.tips('密码不能为空', '#pass',{ tips: 3});
                return false;
            }
            if(cpass == '' ){
                layer.tips('确认密码不能为空', '#cpass',{ tips: 3});
                return false;
            }
            $this.attr('disabled','disabled');
            $.ajax( {
                url:'/Login/resetpass.html',// 跳转到 action
                data:{
                    pass : (pass),
                    cpass:(cpass),
                    token : token,
                    _token:_token
                },
                type:'post',
                cache:false,
                dataType:'json',
                success:function(data) {
                    if(data['status'] == 1){
                        location.replace('/Index/index.html');
                    }else{
                        layer.msg(data['msg'],{ icon:2});
                    }
                    $this.attr('disabled',false);
                },
                error : function() {
                    $this.attr('disabled',false);
                }
            });
        });
    });
    $(".from").bind("keydown",function(e){
        // 兼容FF和IE和Opera
        var theEvent = e || window.event;
        var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
        if (code == 13) {
            //回车执行查询
            $(".full-width").click();
        }
    });

</script>