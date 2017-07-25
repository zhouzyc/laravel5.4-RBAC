
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

<body class="gray-bg" style="background-color: #fff">
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <form id="form" action="#" class="wizard-big wizard clearfix" role="application" novalidate="novalidate">

                    <div style="margin: 0 20px;">
                        <fieldset id="form-p-0" role="tabpanel" aria-labelledby="form-h-0" class="body current" aria-hidden="false">

                            <div class="row">
                                <div style="width: 250px;float: left;margin-top: 10px;margin-left: 17px;">
                                    <input id="mail"  placeholder="邮箱" type="text" class="form-control required" value="">
                                </div>
                                <button type="button" style="margin: 10px;float: left" class="btn btn-warning  btn-sm searchbtn send-code" >发送重置链接</button>
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



    $(document).on('click','.send-code',function(){
        var mail = $('#mail').val();

        if(!mail){
            layer.msg('请输入邮箱', { icon: 2});
            return false;
        }

        var $this = $(this);

        $this.attr('disabled', 'disabled');

        var _token = $('#_token').val();
        $.ajax({
            url: '/Login/forgotpassmail.html',
            data: {
                mail: mail,
                _token: _token
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            success: function (data) {
                if(data['status'] == 1){
                    layer.msg('发送成功', { icon: 1});
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