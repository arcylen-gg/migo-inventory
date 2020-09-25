<form method="post" action="/members/vmoney">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-credit-card"></i> V-MONEY</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label>E-Money Email Address</label>
                            <input class="form-control" type="email" name="vmoney_email">
                        </div>
                        <div class="col-md-4">
                            <label>Amount</label>
                            {{-- min="{{ $minimum }}" max="{{ $wallet }}" --}}
                            <input value="{{ $minimum }}" type="number" class="form-control text-right current-wallet" name="wallet_amount"  step="any">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label>Convenience Fee</label>
                            <input class="form-control convenience-fee" type="text" readonly fixed="{{ $fixed }}" percent="{{ $percent }}">
                        </div>
                        <div class="col-md-4">
                            <label>Tax</label>
                            <input class="form-control tax-fee" type="text" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Total Amount</label>
                            <input class="form-control text-right total-fee" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit"><i class="fa fa-check"></i> Request Payout</button>
    </div>
</form>

<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/vmoney.js"></script>