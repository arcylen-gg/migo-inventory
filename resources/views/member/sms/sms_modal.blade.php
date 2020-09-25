<form class="global-submit" action="/member/maintenance/sms/update" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">Sms Content</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <div class="col-md-12 pull-right">
                    <div class="checkbox">
                        <label><input type="checkbox" name="sms_temp_is_on" value="1" {{isset($sms) ? $sms->sms_temp_is_on ? 'checked' : '' : ''}}>Is Enabled</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">            
                    <label>SMS Content Key</label>
                    <input type="text" class="form-control" value="{{$sms->sms_default_key or ''}}" name="sms_temp_key" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">            
                    <label>SMS Content *</label>
                    <textarea class="form-control input-sm" row="4" name="sms_temp_content" >{{$sms->sms_temp_content or ''}}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save</button>
    </div>
</form>