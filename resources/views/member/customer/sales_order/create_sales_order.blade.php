@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer" id="estimate_form" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="estimate_id" value="{{Request::input('id')}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Create Sales Order</span>
                    <small>
                    
                    </small>
                </h1>
                <!-- <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Save & Edit</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Save & New</button>
                <button type="submit" class="panel-buttons btn btn-custom-default pull-right" data-action="save-and-close">Save & Close</button> -->
               <!--  <a href="javascript:" class="panel-buttons btn btn-custom-white pull-right popup" link="/member/item/add" size="lg">Save</a> -->
                <div class="dropdown pull-right">
                    <div>
                        <a class="btn btn-custom-white" href="/member/customer/sales_order_list">Cancel</a>
                        <button class="btn btn-custom-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu  dropdown-menu-custom">
                            <li><a class="select-action" code="save-and-close">Save & Close</a></li>
                            <li><a class="select-action" code="save-and-edit">Save & Edit</a></li>
                            <li><a class="select-action" code="save-and-print">Save & Print</a></li>
                            <li><a class="select-action" code="save-and-new">Save & New</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray load-data">
        <div class="data-container" >
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-12" style="padding: 30px;">
                        <!-- START CONTENT -->
                        <div style="padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                            </div>
                        </div>
                        <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <select class="form-control droplist-customer input-sm pull-left" name="est_customer_id" data-placeholder="Select a Customer" required>
                                        @include('member.load_ajax_data.load_customer', ['customer_id' => isset($est) ? $est->est_customer_id : (isset($c_id) ? $c_id : '') ]);
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm customer-email" name="est_customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$est->est_customer_email or ''}}"/>
                                </div>
                            </div>
                        </div>                          
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Billing Address</label>
                                <textarea class="form-control input-sm textarea-expand" name="est_customer_billing_address" placeholder="">{{$est->est_customer_billing_address or ''}}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label>Date</label>
                                <input type="text" class="datepicker form-control input-sm" name="est_date" value="{{$est->est_date or date('m/d/y')}}"/>
                            </div>
                        </div>
                        
                        <div class="row clearfix draggable-container">
                            <div class="table-responsive">
                                <div class="col-sm-12">
                                    <table class="digima-table">
                                        <thead>
                                            <tr>
                                                <th style="" ></th>
                                                <th style="">Service Date</th>
                                                <th style="" class="text-right">#</th>
                                                <th style="width: 200px">Product/Service</th>
                                                <th style="">Description</th>
                                                <th style="">U/M</th>
                                                <th style="">Qty</th>
                                                <th style="">Rate</th>
                                                <th style="">Discount</th>
                                                <th style="">Remark</th>
                                                <th style="">Amount</th>
                                                <th style="">Tax</th>
                                                <th width="10"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="draggable tbody-item">     
                                            @if(isset($est))
                                                @foreach($_estline as $estline)
                                                    <tr class="tr-draggable">
                                                        <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                        <td><input type="text" class="for-datepicker" name="estline_service_date[]" value="{{$estline->estline_service_date}}" /></td>

                                                        <td class="invoice-number-td text-right">1</td>
                                                        <td>
                                                            <select class="form-control select-item droplist-item input-sm pull-left {{$estline->estline_item_id}}" name="estline_item_id[]" required>
                                                                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $estline->estline_item_id])
                                                            </select>
                                                        </td>
                                                        <td><textarea class="textarea-expand txt-desc" name="estline_description[]" value="{{$estline->estline_service_date}}"></textarea></td>
                                                        <td>
                                                            <select class="1111 droplist-um select-um {{isset($estline->multi_id) ? 'has-value' : ''}}" name="estline_um[]">
                                                                @if($estline->estline_um)
                                                                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $estline->multi_um_id, 'selected_um_id' => $estline->estline_um])
                                                                @else
                                                                    <option class="hidden" value="" />
                                                                @endif
                                                            </select>
                                                        </td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="estline_qty[]" value="{{$estline->estline_qty}}" /></td>
                                                        <td><input class="text-right number-input txt-rate compute" type="text" name="estline_rate[]" value="{{$estline->estline_rate}}" /></td>
                                                        <td><input class="text-right txt-discount compute" type="text" name="estline_discount[]" value="{{$estline->estline_discount}}{{$estline->estline_discount_type == 'percent' ? '%' : ''}}" /></td>
                                                        <td><textarea class="textarea-expand" type="text" name="estline_discount_remark[]" value="{{$estline->estline_discount_remark}}"></textarea></td>
                                                        <td><input class="text-right number-input txt-amount" type="text" name="estline_amount[]" value="{{$estline->estline_amount}}" /></td>
                                                        <td class="text-center">
                                                            <input type="hidden" class="estline_taxable" name="estline_taxable[]" value="{{$estline->taxable}}" >
                                                            <input type="checkbox" name="" class="taxable-check compute" {{$estline->taxable == 1 ? 'checked' : ''}}>
                                                        </td>
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                @endforeach
                                            @else                                
                                                <tr class="tr-draggable">
                                                    <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                    <td><input type="text" class="for-datepicker" name="estline_service_date[]"/></td>

                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left" name="estline_item_id[]" >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    <td><textarea class="textarea-expand txt-desc" name="estline_description[]"></textarea></td>
                                                    <td><select class="2222 droplist-um select-um" name="estline_um[]"><option class="hidden" value="" /></select></td>
                                                    <td><input class="text-center number-input txt-qty compute" type="text" name="estline_qty[]"/></td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="estline_rate[]"/></td>
                                                    <td><input class="text-right txt-discount compute" type="text" name="estline_discount[]"/></td>
                                                    <td><textarea class="textarea-expand" type="text" name="estline_discount_remark[]" ></textarea></td>
                                                    <td><input class="text-right number-input txt-amount" type="text" name="estline_amount[]"/></td>
                                                    <td class="text-center">
                                                        <input type="hidden" class="estline_taxable" name="estline_taxable[]" value="" >
                                                        <input type="checkbox" name="" class="taxable-check compute" value="checked">
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                                    
                                                <tr class="tr-draggable">
                                                    <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                    <td><input type="text" class="datepicker" name="estline_service_date[]"/></td>
                                                    <td class="invoice-number-td text-right">2</td>
                                                    <td>
                                                        <select class="22222 form-control select-item droplist-item input-sm pull-left" name="estline_item_id[]" >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    <td><textarea class="textarea-expand txt-desc" name="estline_description[]"></textarea></td>
                                                    <td><select class="3333 droplist-um select-um" name="estline_um[]"><option class="hidden" value="" /></select></td>
                                                    <td><input class="text-center number-input txt-qty compute" type="text" name="estline_qty[]"/></td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="estline_rate[]"/></td>
                                                    <td><input class="text-right txt-discount compute" type="text" name="estline_discount[]"/></td>
                                                    <td><input class="text-right number-input" type="text" name="estline_discount_remark[]"/></td>
                                                    <td><input class="text-right number-input txt-amount" type="text" name="estline_amount[]"/></td>
                                                    <td class="text-center">
                                                        <input type="hidden" class="estline_taxable" name="estline_taxable[]" value="" >
                                                        <input type="checkbox" name="" class="taxable-check compute" value="checked">
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Message Displayed on Sales Order</label>
                                <textarea class="form-control input-sm textarea-expand" name="est_message" placeholder="">{{$est->est_message or ''}}</textarea>
                            </div>
                            <div class="col-sm-3">
                                <label>Statement Memo</label>
                                <textarea class="form-control input-sm textarea-expand" name="est_memo" placeholder="">{{$est->est_memo or ''}}</textarea>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        Sub Total
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        <input type="hidden" name="subtotal_price" class="subtotal-amount-input" />
                                        PHP&nbsp;<span class="sub-total">0.00</span>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3  padding-lr-1">
                                                <label>EWT</label>
                                            </div>
                                            <div class="col-sm-3  padding-lr-1">
                                                <!-- <input class="form-control input-sm text-right ewt_value number-input" type="text" name="ewt"> -->
                                                <select class="form-control input-sm ewt-value compute" name="ewt">  
                                                    <option value="0" {{isset($est) ? $est->ewt == 0 ? 'selected' : '' : ''}}></option>
                                                    <option value="0.01" {{isset($est) ? $est->ewt == 0.01 ? 'selected' : '' : ''}}>1%</option>
                                                    <option value="0.02" {{isset($est) ? $est->ewt == 0.02 ? 'selected' : '' : ''}}>2%</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        PHP&nbsp;<span class="ewt-total">0.00</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-4  padding-lr-1">
                                                <select class="form-control input-sm compute discount_selection" name="est_discount_type">  
                                                    <option value="percent" {{isset($est) ? $est->est_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                    <option value="value" {{isset($est) ? $est->est_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2  padding-lr-1">
                                                <input class="form-control input-sm text-right number-input discount_txt compute" type="text" name="est_discount_value" value="{{$est->est_discount_value or ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        PHP&nbsp;<span class="discount-total">0.00</span>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        <div class="row">
                                            <div class="col-sm-4 col-sm-offset-8  padding-lr-1">
                                                <select class="form-control input-sm tax_selection compute" name="taxable">  
                                                    <option value="0" {{isset($est) ? $est->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                    <option value="1" {{isset($est) ? $est->taxable == 1 ? 'selected' : '' : ''}}>Vat (12%)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        PHP&nbsp;<span class="tax-total">0.00</span>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                      Total
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value total">
                                        <input type="hidden" name="overall_price" class="total-amount-input" />
                                        PHP&nbsp;<span class="total-amount">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
            <td><input type="text" class="for-datepicker"  name="estline_service_date[]"/></td>
            <td class="invoice-number-td text-right">2</td>
            <td>
                <select class="form-control select-item input-sm pull-left" name="estline_item_id[]">
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td><textarea class="textarea-expand txt-desc" name="estline_description[]"></textarea></td>
            <td><select class="select-um" name="estline_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="estline_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="estline_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="estline_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="estline_discount_remark[]" ></textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="estline_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" class="estline_taxable" name="estline_taxable[]" value="" >
                <input type="checkbox" name="" class="taxable-check compute" value="checked">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>

@endsection


@section('script')
<script type="text/javascript">
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/customer_estimate.js"></script>
@endsection