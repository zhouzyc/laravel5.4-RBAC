
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{$title}}</title>

    <link href="/css/main.css" rel="stylesheet">
    <link href="/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="/inspinia/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="/inspinia/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="/inspinia/css/animate.css" rel="stylesheet">
    <link href="/inspinia/css/style.css" rel="stylesheet">

    <link href="/inspinia/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="/inspinia/css/plugins/cropper/cropper.min.css" rel="stylesheet">

    <script src="/inspinia/js/jquery-2.1.1.js"></script>
    <script src="/inspinia/js/bootstrap.min.js"></script>
    <script src="/org/layer/layer/layer.js"></script>
    <script src="/org/laydate/laydate/laydate.js"></script>
    <script src="/org/layui/layui/layui.js"></script>


</head>

<body>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            {{--<h2 class="m-r-sm text-muted welcome-message" style="text-align: center;color: #d0e9c6">机器人管理系统</h2>--}}
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element" style="text-align: center">
                        <span>
                            <img alt="image" class="img-circle" style="width: 48px; " src="{{$userData['admin_user_headimg']}}" />
                        </span>
                        <a data-toggle="dropdown" class="dropdown-toggle"  href="#">
                            <span class="clear">
                                <span class="block m-t-xs">
                                    <strong class="font-bold">{{$userData['admin_user_name']}}</strong>
                                </span> <span class="text-muted text-xs block">其它设置 <b class="caret"></b></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="/Index/upuserdata.html">修改资料</a></li>
                            <li><a class="lockScreen">锁屏</a></li>
                            <li class="divider"></li>
                            <li><a class="quitLogin">退出</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        ZH
                    </div>
                </li>
                @foreach ($noteHtml as $html)
                     {!!$html['html']!!}
                @endforeach
            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>

                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">{{$userData['admin_user_name']}}欢迎登录</span>
                    </li>
                    <li>
                        <a class="lockScreen">
                            <i class="fa fa-lock"></i> 锁屏
                        </a>
                    </li>
                    <li>
                        <a class="quitLogin">
                            <i class="fa fa-sign-out"></i> 退出
                        </a>
                    </li>

                </ul>

            </nav>
        </div>
        @if($topTitleHtml)
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    {!!$topTitleHtml!!}
                    <ol class="breadcrumb">
                        {!!$breadcrumbHtml!!}
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        @endif

        <div class="wrapper wrapper-content animated fadeInRight">
            @yield('content')
        </div>

        <div class="adminlogpage">
            @include('Backstage.Layouts.log')

        </div>

        <div class="footer">
            {{--<div class="pull-right">--}}
                {{--10GB of <strong>250GB</strong> Free.--}}
            {{--</div>--}}
            {{--<div>--}}
                {{--<strong>版权</strong> 正合 © 2016-2017--}}
            {{--</div>--}}
        </div>
    </div>
</div>

<input id="_token" type="hidden" name="_token" value="{{ csrf_token()}}"/>

<!-- Mainly scripts -->
<script src="/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="/inspinia/js/plugins/flot/jquery.flot.js"></script>
<script src="/inspinia/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/inspinia/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="/inspinia/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="/inspinia/js/plugins/flot/jquery.flot.pie.js"></script>

<!-- Peity -->
<script src="/inspinia/js/plugins/peity/jquery.peity.min.js"></script>
<script src="/inspinia/js/demo/peity-demo.js"></script>

<!-- Custom and plugin javascript -->
<script src="/inspinia/js/inspinia.js"></script>
<script src="/inspinia/js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI -->
<script src="/inspinia/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="/inspinia/js/plugins/gritter/jquery.gritter.min.js"></script>

<!-- Sparkline -->
<script src="/inspinia/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="/inspinia/js/demo/sparkline-demo.js"></script>

<!-- ChartJS-->
<script src="/inspinia/js/plugins/chartJs/Chart.min.js"></script>

<!-- Toastr -->
<script src="/inspinia/js/plugins/toastr/toastr.min.js"></script>


<script src="/js/main.js"></script>
<script src="/js/md5.js"></script>

<script>

//    $(document).ready(function() {
//        setTimeout(function() {
//            toastr.options = {
//                closeButton: true,
//                progressBar: true,
//                showMethod: 'slideDown',
//                timeOut: 2000
//            };
//            toastr.success('Responsive Admin Theme', 'Welcome to INSPINIA');
//
//        }, 1300);
//    });
</script>
</body>

@yield('script')
</html>
