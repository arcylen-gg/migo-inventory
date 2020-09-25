@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Product Points </span>
                <small>
                    You can set the points of your product here.
                </small>
            </h1>
        </div>
    </div>
</div>
<div class="search-filter-box">
        <div class="col-md-4 col-md-offset-8" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_item_sku" placeholder="Search by item name or sku" aria-describedby="basic-addon1" onChange="search_item(this)">
            </div>
        </div>  
    </div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <form class="global-submit" method="post" action="/member/mlm/product/set/all/points">
                {!! csrf_field() !!}
                <div class="col-md-12 table-responsive">
                    <div class="col-md-12"><center>Set Points/Bonus For All Item</center></div>
                    <div class="col-md-3">
                       <label>Membership</label>
                        <select class="form-control" name="membership_id">
                        @foreach($membership_active as $key => $value)
                            <option  value="{{$value->membership_id}}">{{$value->membership_name}}</option>
                        @endforeach
                        </select> 
                    </div>
                    <div class="col-md-3">
                        <label>Plan</label>
                        <select class="form-control" name="marketing_plan_code_id">
                            @foreach($active_plan_product_repurchase as $key => $value)
                                <option value="{{$value->marketing_plan_code_id}}">{{$value->marketing_plan_label}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Percentage of price</label>
                        <input type="number" class="form-control" value="0" name="percentage">
                    </div>
                    <div class="col-md-3">
                        <label>Set Points</label><br>
                        <button class="btn btn-primary col-md-12">Submit</button>
                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                </form>

                <div class="col-md-12 table-responsive">
                    <div class="load-data" target="productpoints-paginate" @if($item_search != null) item="{{$item_search}}" @endif >
                        <div id="productpoints-paginate">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <!-- <th class="hide">Show in repurchase</th> -->
                                <!-- <th></th> -->
                                <th class="text-center">
                                    <small>Name</small>
                                </th>
                               
                                <th class="text-center">
                                    <small>SKU</small>
                                </th>
                                @if(isset($active_label))
                                    @foreach($active_label as $key => $value)
                                        <th><small>{{$value}}</small></th>                               
                                    @endforeach
                                @endif
                                <th>
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tbl-filter" style="background-color: #F5F5F5">
                        @foreach($item as $variant)
                        <tr>
                            <!-- <td class="hide">
                                <form class="global-submit" id="show_mlm_{{$variant['item_id']}}" action="/member/item/edit/show/mlm" method="post">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="item_id" value="{{$variant['item_id']}}">
                                    <input type="checkbox" class="show_in_mlm_check" item_id="{{$variant['item_id']}}" name="item_show_in_mlm" value="1" @if($variant['item_show_in_mlm'] == 1) checked @endif>
                                </form>
                                
                            </td> -->
                          <!--   <td class="text-center">
                                <img src="{{$variant['item_img']}}" class="img-50-50"></img>
                            </td> -->
                            <td class="text-center">
                                <small><a href="javascript:" class="popup" size="lg" link="/member/item/edit/{{$variant['item_id']}}">{{$variant['item_name']}}</a></small><Br>
                            </td>
                            <td class="text-center">
                               <small>{{$variant['item_sku']}}</small> 
                            </td>
                            @if(isset($active))
                                <form class="global-submit" method="post" id="formvariant{{$variant['item_id']}}" action="/member/mlm/product/point/add">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" form="formvariant{{$variant['item_id']}}">
                                    <input type="hidden" name="item_id" value="{{$variant['item_id']}}" form="formvariant{{$variant['item_id']}}">
                                    @foreach($active as $key => $value)
                                        @if($value == "RANK_REPURCHASE_CASHBACK")
                                            <td style="min-width: 125px;">
                                                <div class="col-md-12 hide">
                                                    <input type="number" class="form-control " name="{{$value}}" value="{{$variant[$value]}}" form="formvariant{{$variant['item_id']}}">
                                                </div>    
                                                @foreach($rank_settings as $key2 => $value2)
                                                <div class="col-md-12">
                                                   <small><label>{{$value2->stairstep_name}}</label> </small>
                                                   <input type="text" class="form-control input-sm" name="rank_cashback_points[{{$value2->stairstep_id}}]" value="{{$variant['rank_cashback'][$value2->stairstep_id]->amount}}" form="formvariant{{$variant['item_id']}}">
                                                </div>
                                                @endforeach
                                            </td>
                                        @else
                                            <td>
                                                <div class="col-md-12 hide">
                                                    <input type="number" class="form-control " name="{{$value}}" value="{{$variant[$value]}}" form="formvariant{{$variant['item_id']}}">
                                                </div>    
                                                @foreach($membership_active as $key2 => $value2)
                                                <div class="col-md-12">
                                                   <small><label>{{$value2->membership_name}}</label> </small>
                                                   <input type="number" class="form-control input-sm" name="membership_points[{{$value}}][{{$value2->membership_id}}]" value="{{$variant['points'][$value2->membership_id]->$value}}" form="formvariant{{$variant['item_id']}}">
                                                </div>
                                                @endforeach
                                            </td>
                                        @endif   
                                    @endforeach
                                </form>    
                            @endif
                            <td>
                              <a href="javascript:" class="pull-right" onClick="save_product_points({{$variant['item_id']}})" >Save</a>  
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="col-md-12 text-center">
                        {!!$_inventory->render()!!}
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    function save_product_points(item_id)
    {
        $('#formvariant'+ item_id).submit();   
    }
    function submit_done(data)
    {
        console.log(data);
        if(data.response_status == "success_edit_points")
    	{
    	    toastr.success('Success! Points Editted');
    	}
        else if(data.response_status == "success_edit_all_points")
        {
            toastr.success('All Product Points Updated');
            location.reload();
        }

    }
    $('.show_in_mlm_check').on('change', function(ito){
        console.log(ito.currentTarget.attributes.item_id);
        var item_id = ito.currentTarget.attributes.item_id.value;
        console.log(item_id);
        $('#show_mlm_' + item_id).submit();
    })
    var link = '/member/mlm/product';
    function search_item(search)
    {
        var item_search = $(search).val();
        window.location = link + '?item=' + item_search;
        
        console.log(item_search);
        var link_load = link + '?item=' + item_search;
        $('#productpoints-paginate').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
        $('#productpoints-paginate').load(link_load + ' #productpoints-paginate');
    }

</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection
