
<form class="global-submit form-to-submit-add" action="/member/pis/ilr/update_count_empties_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Update Physical Count</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                <h4>{{$item->item_name}}</h4>
                <input type="hidden" name="sc_id" value="{{$item->s_cm_item_id}}">
                <input type="hidden" class="form-control" name="sir_id" value="{{$item->sc_sir_id}}">
                <input type="hidden" class="form-control" name="item_id" value="{{$item->item_id}}">
            </div>
        </div>
        @if($issued_um_name)         
        <div class="form-group">
            <div class="col-md-12"> 
                <label>{{$issued_um_name}} quantity *</label>
                <input type="text" class="form-control" name="issued_qty" value="{{$issued_um_qty}}">
                <input type="hidden" class="form-control" name="issued_um_id" value="{{$issued_um_id}}">
            </div>
        </div>
        @endif

        @if($base_name)         
        <div class="form-group">
            <div class="col-md-12"> 
                <label>{{$base_name}} quantity *</label>
                <input type="text" class="form-control" name="base_qty"  value="{{$base_qty}}">
                <input type="hidden" class="form-control" name="base_um_id" value="{{$base_um_id}}">
            </div>
        </div>
        @endif
    
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Update Count</button>
</div>
</form>

<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/truck.js"></script>
<script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->
<script type="text/javascript">
function submit_done(data)
{
    if(data.status == "success-ilr-empties")
    {
        toastr.success("Success");
        $(".empties-container").load("/member/pis/ilr/"+data.id+" .empties-container");
        $('#global_modal').modal('toggle');
    }
    else if(data.status == "success-ilr")
    {       
        toastr.success("Success");
        $(".ilr-container").load("/member/pis/ilr/"+data.id+" .ilr-container");
        $('#global_modal').modal('toggle');
    }
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}
</script>