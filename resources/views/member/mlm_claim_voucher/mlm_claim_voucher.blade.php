@extends('member.layout')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default panel-block panel-title-block" id="top">
            <div class="panel-heading">
                <div>
                    <i class="icon-barcode"></i>
                    <h1>
                        <span class="page-title">Check Voucher</span>
                    </h1>
                    <a href="/member/mlm/claim_voucher" class="panel-buttons btn btn-default pull-right">Back</a>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-block ">
            <div class="col-md-12 form-group-container panel-heading">
                <form id="check-voucher-form" method="post">
                        <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group col-md-6">
                             <label for="voucher id">Voucher ID</label>
                            <input name="voucher_id" value="{{Request::input('voucher_id')}}" class="form-control" id="" placeholder="" type="text">
                        </div>
                        <div class="form-group col-md-6">
                             <label for="voucher id">Voucher Code</label>
                            <input name="voucher_code" value="{{Request::input('voucher_code')}}"  class="form-control" id="" placeholder="" type="text">
                        </div>
                        <div class="form-group col-md-12">
                             <label for="account password">Enter Password</label>
                        @if($_message['account_password'])
                            <div class="form-group col-md-12 alert alert-danger">
                               <ul class = "col-md-12">
                                    @foreach ($_message['account_password'] as $message)
                                        <li> {{$message}} </li>
                                    @endforeach
                                </ul>
                            </div>
                         @endif    
                            <input name="account_password" value="{{Request::input('account_password')}}"  class="form-control" id="" placeholder="" type="password">
                        </div>
                        <div class="form-group col-md-12 text-center">
                            <button type="submit" class="btn btn-default">Check Voucher Code</button> 
                        </div>
                        <div class="form-group col-md-12">
                            @if($_message['voucher_code'] || $_message['voucher_id'])
                                <div class="col-md-12 alert alert-warning">
                                    <ul class = "col-md-12">
                                        @if($_message['voucher_id'])
                                            @foreach ($_message['voucher_id'] as $message)
                                                <li> {{$message}} </li>
                                            @endforeach
                                        @endif
                                        @if($_message['voucher_code'])
                                            @foreach ($_message['voucher_code'] as $message)
                                                <li> {{$message}} </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            @endif     
                        </div>
                </form>

                @if($voucher)
                <form method="POST" action="/" id="claim-voucher-form">
                    <div class="col-md-12 form-group">
                        <div class="col-md-4">
                           <h4> <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                            Product in voucher</h4>
                        </div>
                        @if($voucher->voucher_claim_status == 0)
                            <div class="voucher-stat col-md-4 col-md-offset-4 alert alert-success">
                               <strong> This voucher code is still Unclaimed.  </strong>
                            </div>
                         @elseif($voucher->voucher_claim_status == 1)
                            <div class="voucher-stat col-md-4 col-md-offset-4 alert alert-danger ">
                               <strong> This voucher code was already process.</strong>
                            </div>
                        @else
                            <div class="voucher-stat col-md-4 col-md-offset-4 alert alert-danger">
                            <strong>    This voucher code is cancelled.</strong>
                            </div>    
                        @endif
                    </div>
                    <div class="col-md-12 form-group">
                    <table class="table table-boreded table-striped">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($_voucher_product)
                                <?php $total = 0; ?>
                                @foreach ($_voucher_product as $key=> $voucher_product)
                                    <tr class="{{$voucher_product->voucher_is_bundle == 0 ? '' : 'hide'}}">
                                        <td>{{$voucher_product->item_id}}</td>
                                        <td>{{$voucher_product->item_name}}</td>
                                        <td>{{$voucher_product->item_price}}</td>
                                        <td>{{$voucher_product->voucher_item_quantity}}</td>
                                        <td>{{$voucher_product->voucher_item_quantity * $voucher_product->item_price}}</td>
                                        <?php $total = $total + ($voucher_product->voucher_item_quantity * $voucher_product->item_price); ?>
                                    </tr>
                                @endforeach
                                
                            @endif
                            @foreach($item_bundle as $key => $value)
                                @foreach($value['bundle'] as $key2 => $value2)
                                    <tr>
                                        <td>{{$value2['item_id']}}</td>
                                        <td>{{$value2['item_name']}}</td>
                                        <td>{{$value2['item_price']}}</td>
                                        <td>{{$value2['bundle_qty']}}</td>
                                        <td>{{$value2['bundle_qty'] * $value2['item_price']}}</td>
                                        <?php $total = $total + ($value2['bundle_qty'] * $value2['bundle_qty']); ?>
                                    </tr>
                                @endforeach
                            @endforeach
                            <!-- <tr><td class="text-right" colspan="5">Total: {{$total}} </td></tr> -->
                        </tbody>
                    </table>
                </div>
                <div id="ajax-message" class="col-md-12 form-group">
                </div>
                @if($voucher->voucher_claim_status == 0)
                <div class="col-md-12 form-group">
                      <a type="submit" href="#" class="col-md-offset-4 col-md-4 btn btn-default void-voucher" voucher-id= "{{$voucher->voucher_id}}">Void</a>
                       <button type="submit" class="col-md-4 btn btn-default submit-claim" voucher-id= "{{$voucher->voucher_id}}">Process Claim</button>
                </div>
                @endif
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script type="text/javascript">
    
var process_claim = new process_claim();
function process_claim()
{   


    document_ready();

    function document_ready()
    {
        $(document).ready(function()
        {   
            process_claim_init();
            process_void_init();
        });
    }


    function process_claim_init()
    {
        $('.submit-claim').on('click', function(event)
        {
            $('#ajax-message').empty();
            event.preventDefault();
            var $voucher_id = $(this).attr('voucher-id');
            var $account_password = $('input[name="account_password"]').val();
            var $_token = $('.token').val();
            // console.log($account_password);
            // console.log($voucher_id);
            $.ajax({
                url: '/member/mlm/claim_voucher/check_claim/process',
                type: 'POST',
                dataType: 'json',
                data: {voucher_id: $voucher_id,
                        account_password:$account_password,
                        _token : $_token
                        },
            })
            .done(function($data) {
                if($data['_error'])
                {
                    var $errors = ""; 
                    $.each($data['_error'], function(index, val)
                    {
                        $errors += "<li>"+val+"</li>";
                    });
                    $errors = '<div class="col-md-12 alert alert-danger"><ul>'+$errors+'</ul></div>';
                    
                    $('#ajax-message').append($errors);


                }else
                {
                    var $append = '<div class="col-md-12 alert alert-success"><ul>Voucher successfully claimed.</ul></div>';
                    $('#ajax-message').append($append);
                    $('.voucher-stat').fadeOut('slow', function()
                    {
                        $(this).remove();
                    });
                }
                // if($data['error'])
            })
            .fail(function() {
                // console.log("error");
                $('#ajax-message').empty();
                alert("Something went wrong while claiming voucher.");
            })
            .always(function() {
                // console.log("complete");
            });
            
        });
    }



    function process_void_init()
    {
        $('.void-voucher').on('click', function(event)
        {

            $('#ajax-message').empty();
            event.preventDefault();
            var $voucher_id = $(this).attr('voucher-id');
            var $account_password = $('input[name="account_password"]').val();
            var $_token = $('.token').val();

            $.ajax({
                url: '/member/mlm/claim_voucher/check_claim/void',
                type: 'post',
                dataType: 'json',
                data: {voucher_id: $voucher_id,
                        account_password:$account_password,
                        _token : $_token
                        },
            })
            .done(function($data) {
                if($data['_error'])
                {
                    var $errors = ""; 
                    $.each($data['_error'], function(index, val)
                    {
                        $errors += "<li>"+val+"</li>";
                    });
                    $errors = '<div class="col-md-12 alert alert-danger"><ul>'+$errors+'</ul></div>';
                    
                    $('#ajax-message').append($errors);


                }else
                {
                    var $append = '<div class="col-md-12 alert alert-warning"><ul>Voucher successfully void.</ul></div>';
                    $('#ajax-message').append($append);
                    $('.voucher-stat').fadeOut('slow', function()
                    {
                        $(this).remove();
                    });
                }
                // if($data['error'])
            })
            .fail(function() {
                // console.log("error");
                $('#ajax-message').empty();
                alert("Something went wrong while claiming voucher.");
            })
            .always(function() {
                // console.log("complete");
            });
            
        });
    }
}

</script>
@endsection