@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Binary Promotions</span>
                <small>
                    You can set the computation of your Binary Promotions marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right"> < Back</a>
        </div>
    </div>
</div>
<div class="bsettings">
    {!! $basic_settings !!}  
</div>
<div class="append_settings">
  
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray hide">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
           <div class="table-responsive">
           <table class="table table-condensed">
                <tr><th colspan="6"><center>Binary Promotions</center></th></tr>
               <tr>
                  <td colspan="6">
                    <form class="global-submit" action="/member/mlm/plan/binary_promotions/save" method="post"> 
                    {!! csrf_field() !!}
                      <div class="col-md-2">
                          <label>Membership</label>
                          <select class="form-control" name="binary_promotions_membership_id">
                              @foreach($membership as $key => $value)
                                <option value="{{$value->membership_id}}">{{$value->membership_name}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-md-3">
                            <label>Item</label>
                            <select class="drop-down-item" name="item_id" name="binary_promotions_item_id">
                                @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                            </select>
                      </div>
                      <div class="col-md-2">
                          <label>Required Left</label>
                          <input type="number" class="form-control" value="0" name="binary_promotions_required_left">
                      </div>
                      <div class="col-md-2">
                          <label>Required Right</label>
                          <input type="number" class="form-control" value="0" name="binary_promotions_required_right">
                      </div>
                      <div class="col-md-2">
                          <label>No. Of Units</label>
                          <input type="number" class="form-control" value="0" name="binary_promotions_no_of_units">
                      </div>
                      <div class="col-md-1">
                        <label>Submit</label><br>
                        <button class="btn btn-primary">Save</button>
                      </div>
                      <div class="col-md-12">
                          
                      </div>
                      </form>
                  </td> 
               </tr>
           </table>
           </div>
        </div>
    </div>
</div>        

@endsection

@section('script')
<script type="text/javascript">
load_settings();
    $(".drop-down-item").globalDropList(
    {
    link: '/member/item/add',
    link_size: 'lg',
    maxHeight: "309px",
    width: '100%',
    placeholder: 'Item'
});
    function load_settings()
    {
        $('.append_settings').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
        $('.append_settings').load('/member/mlm/plan/binary_promotions/get');
    }
</script>
@endsection
