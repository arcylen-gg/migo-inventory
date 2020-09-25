
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
    <h4 class="modal-title layout-modallarge-title item_title">{{$process or ''}} APP KEY</h4>
    <input type="hidden" value="{{$app->keys_id or '' }}" name="keys_id">
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">            
                <label>App Name *</label>
                <input type="text" class="form-control" {{isset($app->keys_id) ? 'disabled' : '' }} value="{{$app->social_network_name or ''}}" name="social_network_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Appication ID </label>
                <input type="text" class="form-control" required value="{{$app->app_id or ''}}" name="app_id">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Application Secret *</label>
                <input type="text" class="form-control" required name="app_secret" value="{{$app->app_secret or ''}}">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save App Key</button>
</div>
</form>
