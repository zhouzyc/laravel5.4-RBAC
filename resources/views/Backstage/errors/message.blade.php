<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>信息提示</title>
    <link href="/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/inspinia/css/animate.css" rel="stylesheet">
    <link href="/inspinia/css/style.css" rel="stylesheet">
</head>
<body class="pace-done">
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg" style="margin: 0">
            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig" style="max-width: none">
                    <h1 class="font-bold" style="font-size: 85px;"><?php echo(session('title')); ?></h1>
                    <div class="error-desc" style="font-size: 16px;line-height: 41px;">
                        <?php echo(session('msg')); ?>
                        <br>
                        <p class="jump">
                            页面自动 <a id="href" href="<?php echo session('type') == 1?(session('url')):'javascript:history.go(-1);'; ?>">跳转</a> 等待时间： <b id="wait"><?php echo(session('wait')); ?></b>
                        </p>
                    </div>
                    <input type="hidden"  id="type" value="<?php echo(session('type')); ?>">
                </div>
            </div>
      </div>
    </div>
    <script src="/inspinia/js/jquery-2.1.1.js"></script>
<script type="text/javascript">

    (function(){
        var type = $('#type').val();
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        totaltime=parseInt(wait.innerHTML);
        var interval = setInterval(function(){
            var time = --totaltime;
            wait.innerHTML=""+time;
            if(time === 0) {
                if(type == 2){
                    history.go(-1);
                }else{
                    location.href = href;
                }

                clearInterval(interval);
            };
        }, 1000);
    })();

</script>
</body>
</html>