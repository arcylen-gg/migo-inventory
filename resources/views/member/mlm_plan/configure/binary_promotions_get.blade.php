<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
           <div class="table-responsive">
           <table class="table table-striped table-hover table-condensed">
                <tr><th colspan="6"><center>Binary Promotions</center></th></tr>
               
               @foreach($binary_promotions as $key => $value)
               <tr>
                  <td colspan="6">
                    <form class="global-submit" action="/member/mlm/plan/binary_promotions/edit" method="post"> 
                    {!! csrf_field() !!}
                      <div class="col-md-2">
                          <label>Membership</label>
                          <select class="form-control" name="binary_promotions_membership_id">
                              @foreach($membership as $key2 => $value2)
                                <option value="{{$value2->membership_id}}" @if($value->binary_promotions_membership_id == $key2) selected @endif >{{$value2->membership_name}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-md-2">
                            <label>Item</label>
                            <select class="drop-down-item" name="item_id">
                                @include("member.load_ajax_data.load_item_category", ['add_search' => $value->binary_promotions_item_id, 'item_id' => $value->binary_promotions_item_id])
                            </select>
                      </div>
                      <div class="col-md-2">
                          <label>Required Left </label>
                          <input type="number" class="form-control" value="{{$value->binary_promotions_required_left}}" name="binary_promotions_required_left">
                      </div>
                      <div class="col-md-2">
                          <label>Required Right</label>
                          <input type="number" class="form-control" value="{{$value->binary_promotions_required_right}}" name="binary_promotions_required_right">
                      </div>
                      <!-- Direct and PPV -->
                        <div class="col-md-2">
                            <label>PPV</label>
                            <input type="number" class="form-control" value="{{$value->binary_promotions_repurchase_points}}" name="binary_promotions_repurchase_points">
                        </div>
                        <div class="col-md-2">
                            <label>Direct Referral</label>
                            <input type="number" class="form-control" value="{{$value->binary_promotions_direct}}" name="binary_promotions_direct">
                        </div>
                      <!-- End  -->
                      <div class="col-md-2">
                          <label>No. Of Units</label>
                          <input type="number" class="form-control" value="{{$value->binary_promotions_no_of_units}}" name="binary_promotions_no_of_units">
                      </div>
                      
                        <div class="col-md-2">
                          <label>Promotion Start Date</label>
                          <input type="date" class="form-control" name="binary_promotions_start_date" value="{{Carbon\Carbon::parse($value->binary_promotions_start_date)->format('Y-m-d')}}">
                        </div>
                        <div class="col-md-2">
                          <label>Promotion End Date</label>
                          <input type="date" class="form-control" name="binary_promotions_end_date" value="{{Carbon\Carbon::parse($value->binary_promotions_end_date)->format('Y-m-d')}}">
                        </div>
                        <div class="col-md-2">
                          <label>Submit</label><br>
                          <input type="hidden" class="submit_type" name="submit_type" value="0">
                          <button class="btn btn-primary" name="archive" value="0" onClick="$('.submit_type').val(0)">Edit</button>
                          <button class="btn btn-primary" name="archive" value="1" onClick="$('.submit_type').val(1)">Archive</button>
                        </div>
                      </form>
                  </td> 
               </tr>
               @endforeach
               <tr>
                  <td colspan="6">
                    <form class="global-submit" id="form_a" action="/member/mlm/plan/binary_promotions/save" method="post"> 
                    {!! csrf_field() !!}
                      <div class="col-md-2">
                          <label>Membership</label>
                          <select class="form-control" name="binary_promotions_membership_id">
                              @foreach($membership as $key => $value)
                                <option value="{{$value->membership_id}}">{{$value->membership_name}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-md-2">
                            <label>Item</label>
                            <select class="drop-down-item" name="item_id">
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

                      <!-- Direct and PPV -->
                        <div class="col-md-2">
                            <label>PPV</label>
                            <input type="number" class="form-control" value="0" name="binary_promotions_repurchase_points">
                        </div>
                        <div class="col-md-2">
                            <label>Direct Referral</label>
                            <input type="number" class="form-control" value="0" name="binary_promotions_direct">
                        </div>
                      <!-- End  -->

                      
                          <div class="col-md-2">
                            <label>Promotion Start Date</label>
                            <input type="date" class="form-control" name="binary_promotions_start_date" value="{{$carbon_now}}">
                          </div>
                          <div class="col-md-2">
                            <label>Promotion End Date</label>
                            <input type="date" class="form-control" name="binary_promotions_end_date" value="{{$carbon_now}}">
                          </div>
                      <div class="col-md-2">
                        <label>Submit</label><br>
                        <button class="btn btn-primary" onclick="">Save</button>
                      </div>
                      </form>
                  </td> 
               </tr>
           </table>
           </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".drop-down-item").globalDropList(
    {
    link: '/member/item/add',
    link_size: 'lg',
    maxHeight: "309px",
    width: '100%',
    placeholder: 'Item'
});
</script>