<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Available Credit - {{$customer_name or 'Juan Dela Cruz'}}</h4>
</div>

<form class="global-submit" action="{{$action or ''}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-body">
	<div class="row">
        <div class="clearfix modal-body"> 
            <div class="form-group">
                <div class="col-md-12">
                    <h4> <i class="fa fa-caret-down"></i> Credits</h4>
                </div>
                <div class="col-md-12">
                    @if(count($_cm) > 0)
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center" style="width: 175px">Reference Number</th>
                                <th class="text-center">Total Credit Amount</th>
                                <th class="text-center">Applied Credit</th>
                                <th class="text-center" style="width: 100px">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($_cm as $cm)
                            <tr>
                                <td class="text-center"><input type="checkbox" name="cm_id[]" {{isset($applied[$cm->cm_id]) ? 'checked' : ''}} value="{{$cm->cm_id}}"></td>
                                <td class="text-center">{{$cm->transaction_refnum != "" ? $cm->transaction_refnum : $cm->cm_id}}</td>
                                <td class="text-center">{{currency('PHP',$cm->cm_amount)}}</td>
                                <td class="text-center">{{currency('PHP',$cm->applied_cm_amount)}}</td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-right" name="apply_amount[]" value="{{$cm->cm_amount - $cm->applied_cm_amount}}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>                        
                    </table>
                    @else
                    <label class="text-center form-control">No Transaction</label>
                    @endif
                </div>
            </div>
        </div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
	<button class="btn btn-primary btn-custom-primary" type="submit">Add</button>
</div>
</form>