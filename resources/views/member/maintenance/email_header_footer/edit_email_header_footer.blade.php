
<form class="global-submit form-to-submit-add" action="{{$action}}" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Edit Email {{ ucfirst($type) }}</h4>
    <input type="hidden" value="{{$template->email_template_id or '' }}" name="email_template_id">
    <input type="hidden" value="{{$type}}" name="type">
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
    @if($type == "header")
        <div class="form-group">
            <div class="col-md-4">      
                <input type="hidden" name="header_image" value="{{$template->header_image or ''}}">      
                <label>Header Logo *</label>
                <div class="text-center">
                    <img class="match-height img-responsive image-put image-gallery image-gallery-single" key="1" src="{{ $template->header_image or '/assets/front/img/default.jpg'}}" style="height: 250px;width: 250px; object-fit: cover; border: 1px solid #ddd;margin:auto">
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <div class="col-md-6">            
                        <label>Background Color *</label>
                        <input type="color" class="col-md-12 form-control input-sm" value="{{$template->header_background_color or ''}}" name="header_background_color">
                    </div>
                    <div class="col-md-6">            
                        <label>Header Text Color *</label>
                        <input type="color" class="col-md-12 form-control input-sm" value="{{$template->header_text_color or ''}}" name="header_text_color">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">            
                        <label>Header Text *</label>
                        <textarea class="form-control input-sm tinymce" name="header_txt">{!! $template->header_txt or '' !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($type == "footer")
        <div class="form-group">        
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-6">            
                        <label>Background Color *</label>
                        <input type="color" class="col-md-12 form-control input-sm" value="{{$template->footer_background_color or ''}}" name="footer_background_color">
                    </div>
                    <div class="col-md-6">            
                        <label>Footer Text Color *</label>
                        <input type="color" class="col-md-12 form-control input-sm" value="{{$template->footer_text_color or ''}}" name="footer_text_color">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">            
                        <label>Footer Text *</label>
                        <textarea class="form-control input-sm tinymce" name="footer_txt">{!! $template->footer_txt or '' !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    @endif

    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save {{ $type }}</button>
</div>
</form>

<script type="text/javascript" src="/assets/member/js/tinymce.min.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>

<script type="text/javascript">
    function submit_selected_image_done(data) 
    { 
        var image_path = data.image_data[0].image_path;

        if (data.akey == 1) 
        {
            $('input[name="header_image"]').val(image_path);
            $('.image-put').attr("src", image_path);
        }
        else
        {
            tinyMCE.execCommand('mceInsertContent',false,'<img src="'+image_path+'"/>');
        }
    }
</script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->