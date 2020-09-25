<div class="form-group">
    <div class="col-md-12">         
        <div class="form-group tab-content panel-body warehouse-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                    <thead>

                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Transaction</th>
                            <th>Desription</th>
                            <th>Issue Date</th>
                            <th>Amount</th>
                            <th>Action</th>

                            {{-- <th>Date</th>
                            <th>User</th>
                            <th>Transaction</th>
                            <th>Source</th>
                            <th>New Data</th>
                            <th>Old Data</th>
                             <th>Action</th> --}}

                            <!-- <th></th> -->

                        </tr>
                    </thead>
                    <tbody>

                        @if($_audit != null)
                            @foreach($_audit as $audit)

                                {{-- <tr>
                                    <td>{{date('M d, g:i A',strtotime($audit->created_at))}}</td>
                                    <td>{{$audit->user_id}}</td>
                                    <td>{{ucfirst($audit->remarks)}} </td>
                                    <td>{{$audit->source}}</td>
                                    <td>{{$audit->new_data}}</td>
                                    <td>{{$audit->old_data}}</td>
                                    <!-- <td><a href="">{{$audit->transaction_txt != "" ? "View" : ""}}</a></td> -->
                                </tr> --}}

                    			<tr>
                    				<td>{{date('M d, g:i A',strtotime($audit->audit_created_at))}}</td>
                    				<td>{{$audit->user}}</td>
                    				{{-- <td>{{ucfirst($audit->action)}} <a>{{$audit->transaction_txt}}</a></td> --}}
                    				<td>{{ucfirst($audit->action)}} <a>{{$audit->transaction_txt}}</a></td>
                                    <td>{{$audit->source}}</td>
                    				<td>{{$audit->transaction_date}}</td>
                    				<td>{{$audit->transaction_amount}}</td>
                    		        <td class="text-center"><button class="btn btn-link popup" link="/member/payroll/employee_list/modal_view_all_transaction/{{$audit->audit_trail_id}}/{{$audit->user_id}}" size="lg">History</button></td>
                    			</tr>

                    		@endforeach
                    	@endif

                    </tbody>
                    </table>
                </div>
              {!! str_replace('/?','?',$_audit->render()) !!}   
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
     $('.pagination a').on('click', function (event) {
        event.preventDefault();
        ajaxLoad($(this).attr('href'));
    });

</script>