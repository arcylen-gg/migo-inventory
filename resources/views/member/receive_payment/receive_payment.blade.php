@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}"> 
    <input type="hidden" class="button-action" name="button_action" value="">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Customer &raquo; Receive Payment</span>
                    <small>
                    <!--Add a product on your website-->
                    </small>
                </h1>
                <!-- <button class="panel-buttons btn btn-custom-white pull-right" onclick="window.location='{{ URL::previous() }}'">Cancel</button>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Save</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Save and New</button> -->
                <div class="dropdown pull-right">
                    <div>
                        <a class="btn btn-custom-white" href="/member/vendor/purchase_order/list">Cancel</a>
                        <button class="btn btn-custom-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu  dropdown-menu-custom">
                            <li><a class="select-action" code="save-and-close">Save & Close</a></li>
                            <li><a class="select-action" code="save-and-edit">Save & Edit</a></li>
                            <li><a class="select-action" code="save-and-print">Save & Print</a></li>
                            <li><a class="select-action" code="save-and-new">Save & New</a></li>
                        </ul>
                    </div>

                    <div>
                    </div>
                </div>
                @if(isset($rcvpayment))
                <div class="pull-right">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <!-- <li class="dropdown-header">Dropdown header 1</li> -->
                            <li><a href="/member/accounting/journal/entry/receive-payment/{{$rcvpayment->rp_id}}">Transaction Journal</a></li>
                            <!-- <li class="divider"></li> -->
                            <!-- <li class="dropdown-header">Dropdown header 2</li> -->
                            <li><a href="#">Void</a></li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

<div class="panel panel-default panel-block panel-title-block panel-gray">        
    <div class="tab-content rcvpymnt-container">
        <div class="row rcvpymnt-load-data">
            @include("member.receive_payment.load_content_receive_payment");
        </div>
    </div>
</div>
</form>
@endsection


@section('script')
<script type="text/javascript">
    $(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});
    $('[data-toggle="popover"]').popover(); 
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/receive_payment.js"></script>
@endsection