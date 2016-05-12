<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
    <title>DIMAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="ALCHEMIST Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design"/>
    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);
        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>
    <link href="/css/bootstrap.css" rel='stylesheet' type='text/css'/>
    <link href="/css/style.css" rel='stylesheet' type='text/css'/>
    <script src="/js/jquery-1.11.0.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic'
          rel='stylesheet' type='text/css'>
    <!--Start-smoth-scrolling-->
    <script type="text/javascript" src="/js/move-top.js"></script>
    <script type="text/javascript" src="/js/easing.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".scroll").click(function (event) {
                event.preventDefault();
                $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
            });
        });
    </script>
    <!--end-smoth-scrolling-->
    <!--start-top-nav-script-->
    <script>
        $(function () {
            var pull = $('#pull');
            menu = $('nav ul');
            menuHeight = menu.height();
            $(pull).on('click', function (e) {
                e.preventDefault();
                menu.slideToggle();
            });
            $(window).resize(function () {
                var w = $(window).width();
                if (w > 320 && menu.is(':hidden')) {
                    menu.removeAttr('style');
                }
            });
        });
    </script>
    <!--End-top-nav-script-->
    <!--light-box-files -->
    <script src="/js/jquery.chocolat.js"></script>
    <link rel="stylesheet" href="/css/chocolat.css" type="text/css" media="screen" charset="utf-8"/>
    <!--light-box-files -->
    <script type="text/javascript" charset="utf-8">
        $(function () {
            $('#example1 a').Chocolat();
        });
    </script>
</head>
<body>
<!--start-header-->
<div class="header" id="home">
    <div class="container">
        <div class="logo">
        </div>
        <div class="quote">
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!--end-header-->
@yield('content')
<!--start-footer-->
<div class="footer">
    <div class="container">
        <div class="footer-text">
            <p>Copyright © 2016 DIMAS - Bandung</p>
            <p>Template By<a href="http://w3layouts.com/"> W3layouts</a></p>
            <a href="#home" class="scroll"><img src="/images/top-img.png" alt=""></a>
        </div>
    </div>
</div>
<!--End-footer-->
</body>
</html>