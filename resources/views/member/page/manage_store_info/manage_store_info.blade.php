@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-add" method="post" action="/member/page/store_information/update_submit">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Store Information <i class="fa fa-angle-double-right"></i> Content</span>
                    <small>
                    You can edit store information.
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Update</button>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray">
        <div class="panel-body form-horizontal">
            <div class="tab-content">
                <div class="form-group text-center">
                    <div class="col-md-12">
                        <a href="javascript:" class="show_settings" settings_key="currency">Currency Settings</a> |
                        <a href="javascript:" class="show_settings" settings_key="item_serial">Serial Settings</a> |
                        <a href="javascript:" class="show_settings" settings_key="country">Country Settings</a>                     
                    </div>
                    <div class="col-md-4">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-block panel-title-block panel-gray store-info-container">
        <div class="panel-body form-horizontal">
            <div class="tab-content">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Store Name</label>
                            <input type="text" style="cursor: not-allowed;" readonly class="form-control" value="{{$store_info->shop_key}}" name="store_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Street Address</label>
                            <input value="{{ $store_info->shop_street_address }}" type="text" name="street_address" id="street_address" class="form-control" tabindex="3">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>City</label>
                            <input value="{{$store_info->shop_city}}" type="text" name="city" id="city" class="form-control "  tabindex="5">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Zip/Postal Code</label>
                            <input value="{{ $store_info->shop_zip }}" type="text" name="postal_code" id="postal_code" class="form-control" tabindex="6">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Country</label>
                            <select name="country" class="form-control select-country" tabindex="7">
                                @foreach($_country as $country)
                                <option {{ $store_info->shop_country == $country->country_id ? 'selected' : '' }} name="country" value="{{ $country->country_id }}">{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <label>Phone Number</label>
                            <input value="{{ $store_info->shop_contact }}" type="text" name="contact_number" id="contact_number" class="form-control" tabindex="8">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">
$(".select-country").globalDropList(
    { 
      hasPopup                : "false",
      width                   : "100%",
      placeholder             : "Search Country...",
      no_result_message       : "No result found!"
    });
    function submit_done(data)
    {
        if(data.status == "success")
        {
            toastr.success("Success");
            $(".store-info-container").load("/member/page/store_information .store-info-container"); 
            
        }
        else if(data.status == "error")
        {
            toastr.warning(data.status_message);
            $(data.target).html(data.view);
        }
        else
        {
            $(data.error).each(function( index )
            {
              toastr.warning(data.error[index]);
            });
        }
    }
</script>
@endsection