
<form class="global-submit" action="/member/cashier/sales_agent/add-submit" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Add Sales Agent</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-4">            
                <label>Last Name *</label>
                <input type="text" class="form-control" placeholder="Last Name..." name="last_name" id="warehouse_name">
            </div>
            <div class="col-md-4">            
                <label>First Name *</label>
                <input type="text" class="form-control" name="first_name" placeholder="First Name..." id="warehouse_name">
            </div>
            <div class="col-md-4">            
                <label>Middle Name *</label>
                <input type="text" class="form-control" placeholder="Middle Name..." name="middle_name" id="warehouse_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">          
                <label>Position *</label>
                <select class="form-control drop-down-position" name="position">
                    @include("member.load_ajax_data.load_position")
                </select>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save Agent</button>
</div>
</form>
<script type="text/javascript">
    $(".drop-down-position").globalDropList(
    {
        link : "/member/pis/agent/position/add",
        width: '100%',
        link_size: 'md',
        placeholder: 'Position'
    });
    function submit_done(data)
    {
        if(data.type == "position")
        {        
            toastr.success("Success");
            $(".drop-down-position").load("/member/cashier/sales_agent/add .drop-down-position option", function()
            {                
                 $(".drop-down-position").globalDropList("reload"); 
                 $(".drop-down-position").val(data.id).change();              
            });
            data.element.modal("hide");
        }
    }
</script>
