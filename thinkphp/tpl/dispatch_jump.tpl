{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <link href="http://www.secooler.com/static/css/404.css" rel="stylesheet" type="text/css" />
    <script src="http://www.secooler.com/static/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var h = $(window).height();
            $('body').height(h);
            $('.mianBox').height(h);
            centerWindow(".tipInfo");
        });

            //2.将盒子方法放入这个方，方便法统一调用
            function centerWindow(a) {
                center(a);
                //自适应窗口
                $(window).bind('scroll resize',
                    function() {
                        center(a);
                    });
            }

            //1.居中方法，传入需要剧中的标签
            function center(a) {
                var wWidth = $(window).width();
                var wHeight = $(window).height();
                var boxWidth = $(a).width();
                var boxHeight = $(a).height();
                var scrollTop = $(window).scrollTop();
                var scrollLeft = $(window).scrollLeft();
                var top = scrollTop + (wHeight - boxHeight) / 2;
                var left = scrollLeft + (wWidth - boxWidth) / 2;
                $(a).css({
                    "top": top,
                    "left": left
                });
            }
        </script>
    </head>
    <body>
        <div class="mianBox">
            <img src="http://www.secooler.com/static/images/yun0.png" alt="" class="yun yun0" />
            <img src="http://www.secooler.com/static/images/yun1.png" alt="" class="yun yun1" />
            <img src="http://www.secooler.com/static/images/yun2.png" alt="" class="yun yun2" />
            <img src="http://www.secooler.com/static/images/bird.png" alt="" class="bird" />
            <img src="http://www.pintuer.com/images/ds-1.gif" alt="" class="san" />

            <div class="tipInfo">
                <div class="in">
                    <div class="textThis">
                        <h2><?php switch ($code) {?>
                            <?php case 1:?>
                            <p class="success"><?php echo(strip_tags($msg));?></p>
                            <?php break;?>
                            <?php case 0:?>
                            <p class="error"><?php echo(strip_tags($msg));?></p>
                            <?php break;?>
                            <?php } ?></h2>
                            <p><span>页面自动<a id="href" href="<?php echo($url);?>">跳转</a></span><span>等待<b id="wait"><?php echo($wait);?></b>秒</span></p>
                            <script type="text/javascript">                            (function() {
                                var wait = document.getElementById('wait'), href = document.getElementById('href').href;
                                var interval = setInterval(function() {
                                    var time = --wait.innerHTML;
                                    if (time <= 0) {
                                        location.href = href;
                                        clearInterval(interval);
                                    }
                                    ;
                                }, 1000);
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>



