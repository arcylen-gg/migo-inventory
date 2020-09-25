@extends('member.layout')
@section('content')

    <div class="panel panel-default panel-block panel-title-block clearfix" id="top">
        <div class="panel-body">
        
            <div class="col-md-12">
                <div class="col-md-4">
                    <label>Choose Merchant</label>
                    <select class="form-control choose_merchant">
                        <option value=""></option>
                        @if($selected_merchant)
                            @foreach($merchants as $key => $value)
                                <option value="{{$value->user_id}}" @if($selected_merchant->user_id == $value->user_id) selected @endif >{{$value->user_first_name}} {{$value->user_last_name}}</option>
                            @endforeach
                        @else
                            @foreach($merchants as $key => $value)
                                <option value="{{$value->user_id}}" >{{$value->user_first_name}} {{$value->user_last_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>    
            </div>
            
        </div>
    </div> 
    <div class="app_choose_merchant">
    @if($selected_merchant) 
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <form class="global-submit" method="post" action="/member/merchant/markup/update">
            {!! csrf_field() !!}
            <input type="hidden" name="user_id" value="{{$selected_merchant->user_id}}">
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <label>Set All Item Mark Up</label>
                        <input type="number" class="form-control" value="{{$selected_merchant->user_mark_up_default}}" name="user_mark_up_default">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <label>Set Minimum Mark Up</label>
                        <input type="number" class="form-control" value="{{$selected_merchant->user_mark_up_default}}" name="user_mark_up_lowest">
                    </div>
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <button class="btn btn-primary pull-right">Update mark up</button>
                    </div>
                </div>
            </div>
        </form>    
    </div>    
        @if($items)
        <form class="global-submit" method="post" action="/member/merchant/markup/update/piece">
            {!! csrf_field() !!}
            <input type="hidden" name="user_id" value="{{$selected_merchant->user_id}}">
            <div class="panel panel-default panel-block panel-title-block" id="top">
                <div class="panel-body">
                    <b style="text-transform: uppercase;"><h2>{{$selected_merchant->user_first_name}} {{$selected_merchant->user_last_name}}</h2></b>
                    <button class="btn btn-primary pull-right">Save Mark Up</button>
                    <div class="col-md-12">
                        <br>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th>Item Name</th>
                            <td>Item Price</td>
                            <td>Mark Up Percentage(%)</td>
                            <td>Mark Up Price</td>
                            <td>Item Price <br>After Mark up</td>
                        </tr>

                        <tbody>
                            @foreach($items as $key => $value)
                                <tr>
                                    <td>
                                        {{$value->item_name}}
                                    </td>
                                    <td class="price_{{$key}}">{{$value->item_price}}</td>
                                    @if($value->item_markup_percentage)
                                    <td><input type="number" class="form-control" name="item_markup_percentage[{{$value->item_id}}]" value="{{$value->item_markup_percentage}}" onChange="change_markup(this, {{$key}})"></td>
                                    <td class="mark_up_price_{{$key}}">{{$value->item_markup_value}}</td>
                                    <td>{{$value->item_after_markup}}</td>
                                    @else
                                    <td><input type="number" class="form-control" name="item_markup_percentage[{{$value->item_id}}]" value="0" onChange="change_markup(this, {{$key}})"></td>
                                    <td class="mark_up_price_{{$key}}">0</td>
                                    <td>{{$value->item_price}}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>    
        @endif
    </div>
    @else
        <div class="panel panel-default panel-block panel-title-block" id="top">
            <div class="panel-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Default Markup</th>
                            <th>Minimum Markup</th>
                            <th>Item With Markup</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchants as $key => $value)
                            <tr>
                                <td>{{$value->user_first_name}} {{$value->user_last_name}}</td>
                                <td>{{$value->user_mark_up_default}}</td>
                                <td>{{$value->user_mark_up_lowest}}</td>
                                <td>{{$merchant_count_item[$key]}}   / {{$merchant_count_item_over[$key]}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>    
    @endif
@endsection

@section('script')
<script type="text/javascript">
    $('.choose_merchant').on('change', function(){
        var user_id = $(this).val();
        $('.app_choose_merchant').html('Getting details..');
        $('.app_choose_merchant').load('/member/merchant/markup?user_id=' + user_id + ' .app_choose_merchant');
    });
    function change_markup(ito, key) 
    {
        console.log(key);
        var price = $('.price_' + key).html();
        console.log('price : ' + price);
        var markup = $(ito).val();
        console.log('markup : ' + markup);
        var mark_up_price = (markup/100) * price;
        console.log('mark_up_price : ' + mark_up_price);
        $('.mark_up_price_' + key).html(mark_up_price);

    }
    function submit_done(data)
    {
        if(data.status == 'success')
        {
            toastr.success(data.message);
            var user_id = $('.choose_merchant').val();
            $('.app_choose_merchant').html('Getting details..');
            $('.app_choose_merchant').load('/member/merchant/markup?user_id=' + user_id + ' .app_choose_merchant');
        }

    }
</script>
@endsection