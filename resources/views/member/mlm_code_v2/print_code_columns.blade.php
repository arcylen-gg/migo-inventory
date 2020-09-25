<form action="/member/mlm/print_codes/submit" class="form-print-column" method="get">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">Columns</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <input type="hidden" name="type" value="{{$type}}">
            <div class="clearfix modal-body"> 


                <div class="form-horizontal">
                    <!-- <div class="form-group" style="text-align: center">
                        <div class="col-md-4"></div>
                        <div class="col-md-5">
                             <div class="checkbox">
                                <label>
                                    <input onclick="toggle('.other-settings', this)" type="checkbox" name="register_form" value="register_form">
                                    Print <b>How to Register Forms</b>
                                </label>
                            </div>
                        </div>
                    </div> -->
                   
                </div>
                <div class="form-group"></div>
                <div class="form-horizontal other-settings">
                    <div class="form-group">
                        <div class="col-md-12">
                            @foreach($columns as $col)  
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" {{$col['status'] == true ? 'checked' : ''}} name="{{$col['code']}}">
                                        {{$col['name']}} 
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="">Type of Forms</label>
                            <select name="barcode_type" class="form-control">
                                @if($shop_id == 5)
                                <option value="register_form">Register Form</option>
                                @else
                                <option value="pin_code">Activation Code & Pin</option>
                                @endif
                                <option value="ref_number">Reference Number</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="">Print Code as :</label>
                            <select name="print_code_as" class="form-control">
                                <option value="pdf">PDF</option>   
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group">
                        <div class="col-md-12"><label for="">Range</label></div>
                        <div class="col-md-6">
                            <input type="number" name="print_range_to" class='form-control range-to' value="{{$from or '1'}}"/>
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="print_range_from" class='form-control range-from' value="{{$from or '100'}}"/>
                        </div>
                    </div>

                    @if($type == 'membership_code')
                    <div class="form-group">
                        <div class="col-md-6">
                            <select name="membership" class="form-control">
                                <option value="">All Membership</option>
                            @foreach($_membership as $membership)
                                <option value="{{ $membership->membership_id }}">{{ $membership->membership_name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="membership_kit" class="form-control">
                                <option value="">All Membership Kit</option>
                            @foreach($_item_kit as $kit)
                                <option value="{{ $kit->item_id }}">{{ $kit->item_name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <div class="col-md-12">
                            <select name="item_id" class="form-control">
                                <option value="">All Items</option>
                            @foreach($_items as $item)
                                <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="col-md-12">
                            <select name="status" class="form-control">
                                <option value="">Unused</option>
                                <option value="reserved">Reserved</option>
                                <option value="block">Block</option>
                                <option value="used">Used</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary print-submit" type="button">Print</button>
    </div>
</form>

<script type="text/javascript">
    function toggle(className, obj) {
    var $input = $(obj);
    if ($input.prop('checked')) $(className).slideUp();
    else $(className).slideDown();
    }
    $('.print-submit').unbind('click');
    $('.print-submit').bind('click',function()
    {
        if(parseInt($('.range-from').val()) < parseInt($('.range-to').val()))
        {
            alert('Invalid Range');
        }
        else
        {
            $('.form-print-column').submit();
        }
    });
</script>