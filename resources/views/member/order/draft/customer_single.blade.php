
            <div class="well well-white border-none">
                
                <div class="search-customer-form" style="display: {{$customer_form}}">
                  <div class="form-group">
                      <div class="col-md-12"><label>Find or create a customer</label></div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12">
                          <i class="fa fa-search fa-search-custom" aria-hidden="true"></i>
                          <input type="text" class="form-control search-customer" id="search-customer" placeholder="Find customers..." name="" data-placement="bottom">
                          <div id="popover-customer" class="hide padding0">
                              <div class="customer-list">
                                
                              </div>
                          </div>
                      </div>
                  </div>
                </div>

                <div class="customer-info-div" style="display: {{$customer_info}}">
                  <label class="f18">Customer</label>
                  <a href="#" class="a-close pull-right " data-order="{{$tbl_order_id}}" data-customer="{{$customer_id}}"><i class="fa fa-times"></i></a>
                  <div class="cutomer-result">
                    @if(isset($customer))
                    {!! $customer !!}
                    @endif
                  </div>
                </div>

            </div>
            <div class="well well-white border-none">
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Tags</label>
                    </div>
                    <div class="col-md-4">
                        <a href="#">View all tags</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Urgent, reviewed, wholesale" name="">
                    </div>
                </div>
                <div class="form-group tag-container">
                    
                </div>
            </div>