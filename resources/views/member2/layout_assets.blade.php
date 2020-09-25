{{-- START GLOBAL MODAL --}}
<div id="global_modal" class="modal fade" role="dialog" >
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-content-global clearfix">
        </div>
    </div>
</div>
{{-- END GLOBAL MODAL --}}

{{-- GLOBAL MULTIPLE MODAL --}}
<div class="multiple_global_modal_container"></div>
{{-- END GLOBAL MULTIPLE MODAL --}}


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
<script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/bootstrap.min.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/match-height.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/fit-text.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/slick/slick.min.js"></script>
{{-- GLOBALS --}}
<script type="text/javascript" src="/assets/front/js/jquery.keep-ratio.min.js"></script>
<script type="text/javascript" src="/assets/front/js/globalv2.js"></script>
<script type="text/javascript" src="/assets/front/js/global.js"></script>
{{-- GLOBALS --}}
<script src="/themes/{{ $shop_theme }}/js/global.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/figuesslider.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/parallax.js"></script>

<script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/lightbox/js/lightbox.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/scrollspy.js"></script>
<script src="/themes/{{ $shop_theme }}/assets/easing/jquery.easing.min.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/wow/js/wow.min.js"></script>

<script>
      new WOW().init();
</script>

<!-- FROM HOME.BLADE -->
<!-- <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css"> -->


<!-- FB WIDGET -->
<div id="fb-root"></div>
<script>
    (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<script type="text/javascript" src="/assets/front/js/global_function.js"></script>