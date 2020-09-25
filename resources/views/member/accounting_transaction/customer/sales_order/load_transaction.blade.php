<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Open Transaction - {{$customer_name or 'Juan Dela Cruz'}}</h4>
</div>
<form class="global-submit" action="{{$action or ''}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-body">
	<div class="row">
        <div class="clearfix modal-body"> 
            <div class="form-group">
                <div class="col-md-12">
                    <h4> <i class="fa fa-caret-down"></i> Estimate and Quotation</h4>
                </div>
                <div class="col-md-12">
                    @if(count($_eq) > 0)
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center">Reference Number</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($_eq as $eq)
                            <tr>
                                <td class="text-center"><input type="checkbox"  name="apply_transaction[{{$eq->est_id}}]" {{isset($applied[$eq->est_id]) ? 'checked' : ''}} ></td>
                                <td class="text-center">{{$eq->transaction_refnum != "" ? $eq->transaction_refnum : $eq->est_id}}</td>
                                <td class="text-center">{{currency('PHP',$eq->est_overall_price)}}</td>
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