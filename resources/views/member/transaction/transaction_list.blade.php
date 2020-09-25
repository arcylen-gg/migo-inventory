<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Transaction List</h4>
</div>
<div class="modal-body">
	<div class="form-group">
	    <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">Transaction Number</th>
                        <th class="text-center">Total Amount</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($_list as $key => $list)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td class='text-center'>{{$list->customer_name}}</td>
                            <td>{{$list->transaction_number}}</td>
                            <td>{{currency('PHP',$list->transaction_total)}}</td>
                            <td><a class="popup" size="md" link="/member/cashier/transactions/view_item/{{$list->transaction_list_id}}">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>