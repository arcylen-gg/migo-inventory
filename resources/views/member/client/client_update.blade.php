
<form class="global-submit form-to-submit-add" action="/member/utilities/client/update_submit" method="post">
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="user_id" value="{{$shop->user_id}}">
<input type="hidden" name="shop_id" value="{{$shop->shop_id}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Update Client</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">            
                <label>First Name *</label>
                <input type="text" class="form-control" value="{{$shop->user_first_name}}" name="first_name" id="warehouse_name">
            </div>
            <div class="col-md-6">            
                <label>Last Name *</label>
                <input type="text" class="form-control" value="{{$shop->user_last_name}}" name="last_name" id="warehouse_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">            
                <label>Email address *</label>
                <input type="text" class="form-control" value="{{$shop->user_email}}" name="email_address">
            </div>
        </div>         
        <div class="form-group">
            <div class="col-md-12 text-left">            
                <input type="checkbox" onclick="toggle('.update-password', this)" value="update_password" class="up-check" name="update_password"> <label>Update Password</label>
            </div>
        </div>
        <div class="form-group update-password" style="display: none" >          
            <div class="col-md-12">            
                <label>Update Password *</label>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" class="form-control" placeholder="Enter Old Password" name="old_password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" class="form-control" placeholder="Enter New Password" name="new_password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" class="form-control" placeholder="Confirm New Password" name="confirm_password">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Update Info</button>
</div>
</form>
<script type="text/javascript">
    function toggle(className, obj)
    {
        var $input = $(obj);
        if ($input.prop('checked')) $(className).slideDown();
        else $(className).slideUp();
    }
    function submit_done(data)
    {
        if(data.status == "no_changes_made")
        {
            toastr.success("Success! No changes has been made");
            $('#global_modal').modal('toggle');
        }
        else if(data.status == "success_update")
        {
            toastr.success("Update Success! Please Re-Login Account");
            location.href = data.redirect;
        }
        

    }
</script>
<!-- <script type="text/javascript" src="/assets/member/js/truck.js"></script> -->
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->