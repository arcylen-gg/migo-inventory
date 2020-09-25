
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
    <h4 class="modal-title layout-modallarge-title item_title">Email Content</h4>
    <input type="hidden" value="{{$email_content->email_content_id or '' }}" name="email_content_id">
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">            
                <label>Email Content Key *</label>
                <input type="text" class="form-control" value="{{$email_content->email_content_key or ''}}" name="email_content_key" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Email Subject </label>
                <input type="text" class="form-control" value="{{$email_content->email_content_subject or ''}}" name="email_content_subject" readonly="readonly" >
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Email Content *</label>
                <textarea class="form-control input-sm tinymce" name="email_content">{!! $email_content->email_content or '' !!}</textarea>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Email Content</button>
</div>
</form>

<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->