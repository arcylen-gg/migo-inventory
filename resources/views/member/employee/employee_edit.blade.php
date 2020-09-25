
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
    <h4 class="modal-title layout-modallarge-title item_title">Edit Agent</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
<input type="hidden" name="employee_id" value="{{$edit->employee_id}}">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-4">            
                <label>Last Name *</label>
                <input type="text" class="form-control" value="{{$edit->last_name}}" placeholder="Last Name..." name="last_name" id="warehouse_name">
            </div>
            <div class="col-md-4">            
                <label>First Name *</label>
                <input type="text" class="form-control" value="{{$edit->first_name}}" name="first_name" placeholder="First Name..." id="warehouse_name">
            </div>
            <div class="col-md-4">            
                <label>Middle Name *</label>
                <input type="text" class="form-control" value="{{$edit->middle_name}}" placeholder="Middle Name..." name="middle_name" id="warehouse_name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">          
                <label>Position *</label>
                <select class="form-control drop-down-position" name="position">
                    @include("member.load_ajax_data.load_position",['position_id' => $edit->position_id])
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <label>Email Address</label>
                <input type="text" class="form-control" name="email_address" value="{{$edit->email}}">
            </div>
            <div class="col-md-6">
                <label>Username</label>
                <input type="text" class="form-control" name="username" value="{{$edit->username}}">
            </div>
            <div class="col-md-6">
                <label>Password</label>
                <input type="password" class="form-control" name="password" value="{{Crypt::decrypt($edit->password)}}">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Update Agent</button>
</div>
</form>

<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/employee.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->