@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">{{$page or ''}}</span>
            <small>
            Reset Accounting/Inventory
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button class="btn btn-primary reset-btn" type="button"><i class="fa fa-star"></i> Reset</button>
            </div>
        </div>
    </div>
</div>
<form class="global-submit form-submit" action="{{$action or ''}}" method="post">
    {!! csrf_field() !!}
<input type="hidden" name="entry_pass" class="encrypt-pass" value="">
<input type="hidden" name="encrypt_pass" value="{{$encrypt_pass}}">
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <div class="panel-body form-horizontal">
      <div class="form-group">
          <div class="col-md-5"></div>
          <div class="col-md-4">
            <div class="form-group check-list">
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-all" name="">Select All</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li items-check" name="reset_transaction[]" value="items">  Items</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li category-check" name="reset_transaction[]" value="category"> Item Category</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li um-check" name="reset_transaction[]" value="um">  Unit of Measurement</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li transaction-check" name="reset_transaction[]" value="transaction">  Transaction</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li warehouse-check" name="reset_transaction[]" value="warehouse">  Warehouse</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li initial-inventory-check" name="reset_transaction[]" value="initial_inventory"> Initial Inventory</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li inventory-check" name="reset_transaction[]" value="inventory">  Inventory</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li customer-check" name="reset_transaction[]" value="customer">  Customer Listing</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li vendor-check" name="reset_transaction[]" value="vendor">  Vendor Listing</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li journal_entry-check" name="reset_transaction[]" value="journal_entry">  Journal Entry</label>
                </div>
                <div class="col-md-12 checkbox">
                    <label> <input type="checkbox" class="check-li coa-check" name="reset_transaction[]" value="coa">  Chart of Account</label>
                </div>
            </div>
          </div>
      </div>
    </div>
</div>
</form>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/transaction_reset.js"></script>
@endsection