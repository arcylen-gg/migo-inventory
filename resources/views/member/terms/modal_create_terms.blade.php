<form class="global-submit" role="form" action="/member/maintenance/terms/terms" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" name="terms_id" value="{{$terms->terms_id or ''}}" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">New Term</h4>
    </div>

    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            <div class="row clearfix">
                <div class="col-md-12">
                    <!-- START CONTENT -->
                    <div class="form-group">
                        <div class="col-md-12">
                           <label>Name</label>
                           <input type="text" name="terms_name" class="form-control input-sm" value="{{$terms->terms_name or ''}}"required>        
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                           <label>Due in fixed number of days</label>
                           <input type="text" class="form-control input-sm int-format" name="terms_no_of_days" value="{{$terms->terms_no_of_days or ''}}" required>                    
                        </div>
                    </div>
                    <!-- END CONTENT -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save</button>
    </div>
</form>

<script type="text/javascript">
var terms = new terms();

    function terms()
    {
        init();

        function init()
        {
            action_initialize_select();
        }

        function action_initialize_select()
        {

        }
    }
</script>