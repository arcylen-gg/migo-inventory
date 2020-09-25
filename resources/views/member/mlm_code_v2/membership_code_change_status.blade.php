<form action="/member/mlm/code2/change_status" method="post" class="global-submit">
    {{ csrf_field() }}
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">{{strtoupper($action)}} REMARKS</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <div class="clearfix modal-body"> 
                <input type="hidden" name="action_status" value="{{$action}}">
                <input type="hidden" name="record_log_id" value="{{$record_log_id}}">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <h3>{{$item->item_name}}</h3>
                        </div>
                    </div>
                    @if($action == 'reserved')
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Select Customer (Optional)</label>
                        </div>
                        <div class="col-md-12">
                            <select name="reserved_customer"  class="select-customer form-control">
                                @include('member.load_ajax_data.load_customer')
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="col-md-12">
                            <textarea class="form-control" name="remarks" placeholder="Enter remarks here..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary assemble-code-submit" type="submit">{{ucfirst($action)}}</button>
    </div>
</form>
<script type="text/javascript">
    $('.select-customer').globalDropList(
    { 
        width : "100%",
        link : "/member/customer/modalcreatecustomer"
    });
</script>