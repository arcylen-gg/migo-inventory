
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">STATUS</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container" >
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">            
                <h3>SIR NO : {{sprintf("%'.05d\n", $sir->sir_id)}}</h3>
            </div>
            <div class="col-md-6">
                <h3>Plate Number: {{$sir->plate_number}}</h3>
            </div>
        </div>
        <div class="form-group" style="font-size: 20px">
            {!! $sir->gen_status !!}
       <!--  <div class="col-md-12"><h3>Select Item</h3></div>        
            <div class="col-md-3" >
                <select class="form-control">
                    <option>All Items</option>
                </select>
            </div>
            <div class="col-md-9" > 
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control" placeholder="Start typing item">
                </div>
            </div>
        </div> -->    
    </div>
</div>

<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
</div>

<script type="text/javascript">   
function submit_done(data)
{
    if(data.status == "success")
    {
       toastr.success("Success");
       location.reload();
    }
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}
</script>