@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="debit_memo_id" value="{{$debit_memo_id}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Debit Memo - Replace</span>
                    <small>
                    
                    </small>
                </h1>
                <a href="/member/vendor/debit_memo/list" class="panel-buttons btn btn-custom-white pull-right">Cancel</a>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Save</button>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray">
       <!--  <ul class="nav nav-tabs">
            <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes"><i class="fa fa-star"></i> Invoice Information</a></li>
            <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used-codes"><i class="fa fa-list"></i> Activities</a></li>
        </ul> -->
        <div class="tab-content">
            <div class="row">
                <div class="col-md-12" style="padding: 30px;">
                    <!-- START CONTENT -->
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="form-control droplist-vendor input-sm pull-left" name="db_vendor_id" data-placeholder="Select a Vendor" required>
                                    @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($db->db_vendor_id) ? $db->db_vendor_id : '']);
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm vendor-email" name="db_vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$db->db_vendor_email or ''}}"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-2">
                            <label>Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="db_date" value="{{isset($db->db_date) ? $db->db_date : date('m/d/y')}}"/>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container db-replace-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 10px;" ></th>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 180px;">Product/Service</th>
                                            <th style="width: 120px;">QTY</th>
                                            <th style="width: 100px;">Rate</th>
                                            <th style="width: 100px;">Amount</th>
                                            <th style="width: 20px;"></th>
                                            <th style="width: 120px;">Replace QTY</th>
                                            <th style="width: 100px;">Rate</th>
                                            <th style="width: 100px;">Amount</th>
                                            <th style="width: 100px;">Total Amount</th>
                                            @include("member.load_ajax_data.load_th_serial_number")
                                            <th style="width: 100px;">Condemned</th>
                                        </tr>
                                    </thead>
                                    <tbody class="draggable tbody-item">     
                                        @foreach($_db_item as $dbline)
                                            <tr class="tr-draggable">
                                                <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                <td class="invoice-number-td text-right">1</td>
                                                <td><span>{{ $dbline->item_name}}</span></td>
                                                <td><span>{{$dbline->dbline_qty_um}}</span></td>
                                                <td><span>{{number_format($dbline->dbline_rate,2)}}</span></td>
                                                <td><span>{{number_format($dbline->dbline_amount,2)}}</span></td>
                                                <td><i size="sm" link="/member/vendor/debit_memo/replace_item/{{$dbline->dbline_id}}" class="popup btn btn-custom-white fa fa-upload"></i></td>
                                                <td><span>{{$dbline->dbline_replace_qty_um}}</span></td>
                                                <td><span>{{number_format($dbline->dbline_replace_rate,2)}}</span></td>
                                                <td><span>{{number_format($dbline->dbline_replace_amount,2)}}</span></td>
                                                <td>
                                                <input type="hidden" name="" class="txt-amount" value="{{$dbline->dbline_amount - $dbline->dbline_replace_amount}}">
                                                <span>{{number_format(($dbline->dbline_amount - $dbline->dbline_replace_amount),2)}}</span></td>
                                                @if(isset($serial)) 
                                                <td>
                                                    <textarea class="txt-serial-number" name="serial_number[]">{{$dbline->serial_number}}</textarea>
                                                </td>
                                                @endif
                                                <td class="text-center"><input disabled type="checkbox" {{$dbline->dbline_replace_status == 1 ? 'checked' : '' }} name=""></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Vendor Message</label>
                            <textarea class="form-control input-sm textarea-expand" name="db_message" placeholder="">{{$db->db_message or ''}}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label>Statement Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="db_memo" placeholder="">{{$db->db_memo or ''}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Total
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    <input type="hidden" name="subtotal_price" class="subtotal-amount-input" />
                                    PHP&nbsp;<span class="sub-total">0.00</span>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Remaining Total
                                </div>
                                <div class="col-md-5 text-right digima-table-value total">
                                    <input type="hidden" name="overall_price" class="total-amount-input" />
                                    PHP&nbsp;<span class="total-amount">0.00</span>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                    
                    <!-- END CONTENT -->
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/debit_memo.js"></script>
@endsection