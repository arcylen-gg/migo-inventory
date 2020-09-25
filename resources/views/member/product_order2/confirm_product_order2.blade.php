<form class="global-submit" method="post" action="{{$action or  ''}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="transaction_list_id" value="{{ $transaction->transaction_list_id }}">
    <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_id }}">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">{{$page or ''}}</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group text-center">
                        <h3><strong>{{$title or ''}}</strong> payment of <strong>{{ucwords($transaction->first_name.' '.$transaction->middle_name.' '.$transaction->last_name)}}</strong></h3>
                        <h3> worth of <strong>{{currency('PHP ',$transaction->transaction_total)}}</strong> ?</h3>
                    </div>
                    @if($title == 'CONFIRM')
                    <div class="form-group text-center">
                        <h3>
                            <a target="_blank" href="/member/ecommerce/product_order2/proof?id={{$transaction->transaction_list_id}}">View proof here</a>
                        </h3>
                    </div>
                    @endif
                </div>
            </div>
    	</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary" type="submit">{{$title or ''}}</button>
    </div>
</form>