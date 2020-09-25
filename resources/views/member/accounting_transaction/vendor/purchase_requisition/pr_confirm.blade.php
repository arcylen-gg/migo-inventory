<form class="global-submit" method="post" action="/member/transaction/purchase_requisition/confirm-submit">
    <input type="hidden" value="{{ csrf_token() }}" name="_token">
    <input type="hidden" value="{{$pr->requisition_slip_id}}" name="id">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">Confirm Purchase Requisition</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <select class="form-control" name="status"><!-- 
                    <option value="pending">Pending</option> -->
                    <option value="confirm">Confirm</option>
                </select>
            </div>
            <div class="form-group">
            	<label>Attach Proof of Issuing</label>
                <input type="hidden" class="upload_image_1" name="image" value="{{ $pr->confirm_image != null ? $pr->confirm_image : '/assets/front/img/default.jpg'}}">
                <img class="match-height img-responsive image-put-1 image-gallery image-gallery-single" key="1" src="{{ $pr->confirm_image != null ? $pr->confirm_image : '/assets/front/img/default.jpg'}}" style="height: 200px;width: 200px; object-fit: cover; border: 1px solid #ddd;margin:auto">
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" >Confirm</button>
    </div>
</form>
<script type="text/javascript">
function submit_selected_image_done(data) 
{ 
    var image_path = data.image_data[0].image_path;

    if(data.akey != 0) 
    {
        $('.upload_image_'+data.akey).val(image_path);
        $('.image-put-'+data.akey).attr("src", image_path);
    }
}
</script>