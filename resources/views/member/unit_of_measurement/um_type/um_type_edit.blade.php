
<form class="global-submit form-to-submit-add" action="/member/item/um_type/edit_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="um_type_id" value="{{$edit->um_type_id}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Edit U/M Type</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">

        <div class="form-group">
            <div class="col-md-12">            
                <label>U/M Type *</label>
                <select class="form-control" required name="um_type_parent" {{$edit->um_type_parent_id == 0 ? 'disabled' : ''}} >
                @if($_um_type_parent)             
                        <option value="0">This is a parent Type</option>
                    @foreach($_um_type_parent as $type)
                        <option value="{{$type->um_type_id}}" {{$edit->um_type_parent_id == $type->um_type_id ? 'selected=selected' : ''}}>{{$type->um_type_name}}</option>
                    @endforeach
                @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>U/M Type Name *</label>
                <input type="text" required class="form-control" name="um_type_name" value="{{$edit->um_type_name}}" id="um_type_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>U/M Type Abbreviation *</label>
                <input type="text" class="form-control" name="um_type_abbre" value="{{$edit->um_type_abbrev}}" id="um_type_abbre">
            </div>
        </div>  
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save U/M Type</button>
</div>
</form>
<script type="text/javascript">
function submit_done(data)
{
    if(data.status == "success")
    {       
        toastr.success("Success");
        $(".um-container").load("/member/item/um_type .um-container"); 
        $(data.target).html(data.view);
        $('#global_modal').modal('toggle');
    }
    else
    {       
        toastr.warning(data.status_message);
        $(data.target).html(data.view); 
    }

}
</script>