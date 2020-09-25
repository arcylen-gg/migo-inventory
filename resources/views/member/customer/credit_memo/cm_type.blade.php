
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Confirm</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12 text-center">
        @if(isset($for_tablet))
            <div class="form-group">
                <div class="col-md-12 col-xs-12">
                    <a class="btn btn-def-white btn-custom-blue form-control" href="/tablet/credit_memo">Returns</a>
                </div>
            </div>
            <br>
            <br>
            <div class="form-group">
                <div class="col-md-12 col-xs-12">
                    <a class="btn btn-def-white btn-custom-white form-control" href="/member/customer/credit_memo/update_action?type=others{{isset($tablet) ? '_tablet' : '' }}&sir_id={{Session::get('sir_id')}}&cm_id={{$cm_id or ''}}">Others</a>
                </div>
            </div>
            <br>
            <br>
            <div class="form-group">
                <div class="col-md-12 col-xs-12">
                    <a class="btn btn-def-white btn-custom-white form-control popup" size="lg" link="/tablet/customer/credit_memo/update_action?type=invoice_tablet&sir_id={{Session::get('sir_id')}}&cm_id={{$cm_id or ''}}">Apply to an Invoice</a>
                </div>
            </div>
            @else
            <div class="form-group">
                <div class="col-md-12 col-xs-12">
                    <a class="btn btn-custom-blue form-control" href="/member/customer/credit_memo/update_action?type=retain_credit&cm_id={{$cm_id or ''}}">Retains as Available Credit</a>
                </div>
            </div>
            <br>
            <br>
            <div class="form-group">
                <div class="col-md-12 col-xs-12">
                    <a class="btn btn-success form-control" href="/member/customer/credit_memo/update_action?type=refund&cm_id={{$cm_id or ''}}">Give a Refund</a>
                </div>
            </div>
            <br>
            <br>
            <div class="form-group">
                <div class="col-md-12 col-xs-12">
                    <a class="btn btn-def-white btn-custom-white form-control popup" size="lg" link="/member/customer/credit_memo/update_action?type=invoice&cm_id={{$cm_id or ''}}">Apply to an Invoice</a>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <div class="pull-right"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">Close</button></div>
</div>