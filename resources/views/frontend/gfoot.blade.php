{{-- MODAL --}}
<div id="global_modal" class="modal fade" role="dialog" >
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content modal-content-global clearfix">
        </div>
    </div>
</div>
<div class="multiple_global_modal_container"></div>
{{-- JQUERY --}}
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{{-- BOOTSTRAP --}}
<script src="/assets/initializr/js/vendor/bootstrap.min.js"></script>
{{-- EXTERNAL --}}
<script type="text/javascript" src="/assets/front/external_js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="/assets/front/external_js/match-height.js"></script>
<script type="text/javascript" src="/assets/front/external_js/fit-text.js"></script>
<script type="text/javascript" src="/assets/front/external_js/figuesslider.js"></script>
<script type="text/javascript" src="/assets/front/external_js/parallax.js"></script>
<script type="text/javascript" src="/assets/front/external_js/scrollspy.js"></script>
<script type="text/javascript" src="/assets/front/external_js/jquery.keep-ratio.min.js"></script>
<script type="text/javascript" src="/assets/front/external_js/footable.js"></script>
<script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
{{-- SLICK --}}
<script type="text/javascript" src="/assets/slick/slick.min.js"></script>
{{-- LIGHTBOX --}}
<script type="text/javascript" src="/assets/lightbox/js/lightbox.js"></script>
<!-- LIGHTSPEEDBOX -->
<script type="text/javascript" src="/assets/lightspeedbox/lsb.min.js"></script>
{{-- EASING --}}
<script type="text/javascript" src="/assets/easing/jquery.easing.min.js"></script>
{{-- WOW --}}
<script type="text/javascript" src="/assets/wow/js/wow.min.js"></script>
<script>
      new WOW().init();
</script>
<!-- FOR GOOGLE LOGIN -->
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/api:client.js"></script>
{{-- GLOBALS --}}
<script type="text/javascript" src="/assets/front/js/global_function.js"></script>
<script type="text/javascript" src="/assets/front/js/globalv2.js"></script>
<script type="text/javascript" src="/assets/front/js/global_cart.js"></script>
<script type="text/javascript">
$(document).on('show.bs.modal', '.modal', function () 
{
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});
</script>

<!-- FOR GOOGLE LOGIN -->
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/api:client.js"></script>
<!-- END GOOGLE LOGIN -->