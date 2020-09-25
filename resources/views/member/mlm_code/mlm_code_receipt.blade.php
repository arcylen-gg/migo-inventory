@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Membership Codes Receipt</span>
                <small>
                    The membership code receipt are shown here.
                </small>
            </h1>
            <!-- <a href="/member/mlm/code/sell" class="panel-buttons btn btn-primary pull-right">Sell Codes</a> -->
            <a href="/member/mlm/code" class="panel-buttons btn btn-default pull-right">Back</a>
        </div>
    </div>
</div>
<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#all"><i class="fa fa-star"></i> All Receipt</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-6 col-md-offset-2" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="search by customer name" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="tab-content codes_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <div class="load-data" target="reciept-paginate">
                    <div id="reciept-paginate">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer Name</th>
                            <th>Invoice Date Created</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_invoice as $invoice)
                        <tr>
                            <td>{{$invoice->membership_code_invoice_id}}</td>
                            <td>{{$invoice->membership_code_invoice_f_name}} {{$invoice->membership_code_invoice_m_name}} {{$invoice->membership_code_invoice_l_name}}</td>
                            <td>{{$invoice->membership_code_date_created}}</td>
                            <td>
                                <a href="javascript:" onClick="append_a('/member/mlm/code/receipt/view/{{$invoice->membership_code_invoice_id}}')" link="/member/mlm/code/receipt/view/{{$invoice->membership_code_invoice_id}}">
                                    View
                                </a>

                            </td>
                            <td class="">
                                <a href="/member/mlm/code/receipt/view/{{$invoice->membership_code_invoice_id}}?pdf=true" target="_blank">PDF</a>
                            </td>
                        </tr> 
                        @endforeach
                    </tbody>
                </table>
                <center>{!! $_invoice->render() !!}</center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6">
    
    <div class="tab-content">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <center>Receipt</center>
                <div class="append_reciept">
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
    var typingTimer;                //timer identifier
    var doneTypingInterval = 350;  //time in ms
    
    $('.search_name').on('input', function() 
    {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneSearch, doneTypingInterval);
    });
    @if(Request::input('invoice') != null)
        $('.append_reciept').html('<center><div style="margin: 20px auto;" class="loader-16-gray"></div></center>');
        $('.append_reciept').load("/member/mlm/code/receipt/view/{{Request::input('invoice')}}");
    @endif
    function append_a(link)
    {
        $('.append_reciept').html('<center><div style="margin: 20px auto;" class="loader-16-gray"></div></center>');
        $('.append_reciept').load(link);
    }
    function on_search()
    {
        var typingTimer;                //timer identifier
        var doneTypingInterval = 350;  //time in ms
        
        $('.search_name').on('input', function() 
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneSearch, doneTypingInterval);
        });
    }

    function doneSearch() 
    {
       var request2 = $('.search_name').val();
       var url         = encodeURI("/member/mlm/code/receipt?customer_name="+request2);
       $(".codes_container").load(url+" .codes_container");
    }
    function show_load_container()
    {
        $(".codes_container").html('<center><div class="loader-16-gray"></div></center>');
    }
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection