<form class="global-submit form-horizontal" role="form" action="{{$action}}" id="confirm_answer" method="post">
{!! csrf_field() !!}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">U/M</h4>
    <input type="hidden" name="um_type" value="{{$um_type or ''}}">
    <input type="hidden" name="um_id" value="{{$id or 0}}">
</div>
<div class="modal-body clearfix">
    <div class="col-md-12">
        <label>U/M Name</label>
        <input type="text" class="form-control" name="um_name" value="{{$um->um_name or ''}}">
    </div>
    <div class="col-md-12">
        <label>U/M Abbrev</label>
        <input type="text" class="form-control" name="um_abbrev" value="{{$um->um_abbrev or ''}}">
    </div>
</div>
<div class="modal-footer">
    <div class="col-md-6 col-xs-6"><button type="submit" class="btn btn-custom-blue form-control">Save</button></div>
    <div class="col-md-6 col-xs-6"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">Cancel</button></div>
</div>  
</form>