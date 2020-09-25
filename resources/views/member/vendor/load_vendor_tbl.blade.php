
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th class="text-left">Company Name</th>
                <th class="text-left">Contact Person</th>
                <th class="text-left">Contact Details</th>
                <th class="text-center">Balance Total</th>

                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($_vendor) > 0)
            @foreach($_vendor as $vendor)
             <tr class="cursor-pointer" id="tr-vendor-{{$vendor->vendor_id_s}}">
                <td class="col-md-5 text-left">{{$vendor->vendor_company}}</td>
                <td class="col-md-2 text-left">{{$vendor->vendor_title_name.' '.$vendor->vendor_first_name.' '.$vendor->vendor_middle_name.' '.$vendor->vendor_last_name.' '.$vendor->vendor_suffix_name}}</td>
                <td class="col-md-3 text-left">
                    Tel No : {{$vendor->ven_info_phone != "" ? $vendor->ven_info_phone : "---"}}<br>
                    Mobile : {{$vendor->ven_info_mobile != "" ? $vendor->ven_info_mobile : "---" }}<br>
                    Fax : {{$vendor->ven_info_fax != "" ? $vendor->ven_info_fax : "---"}}<br>
                    Email Address : <a target="_blank" {{$vendor->vendor_email != "" ? 'href=https://mail.google.com/mail/?view=cm&fs=1&to='.$vendor->vendor_email : '' }}>{{$vendor->vendor_email != "" ? $vendor->vendor_email : "---" }}</a>
                </td>
                <td class="col-md-3 text-left">{{currency("PHP",$vendor->balance)}}</td>
                <td class="col-md-2 text-center">
                    <!-- ACTION BUTTON -->
                    @if($vendor->archived == 0)
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-custom-white  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li><a href="/member/vendor/purchase_order?vendor_id={{$vendor->vendor_id}}">Purchase Order</a></li>
                        <li><a href="/member/vendor/create_bill?vendor_id={{$vendor->vendor_id}}">Create Bill</a></li>
                        <!-- <li><a href="/member/vendor/estimate">Create Estimate</li> -->
                        <li><a href="javascript:" class="popup" link="/member/vendor/archived/{{$vendor->vendor_id}}/inactive" size="md">Make Inactive</a></li>
                        <!-- <li><a href="javascript:" data-html="inactive">Make Inactive</a></li> -->
                        <li><a href="javascript:" class="popup" link="/member/vendor/edit/{{$vendor->vendor_id}}" size="lg">Edit vendor Info</a></li>
                        <li ><a class="popup" link="/member/vendor/tag/{{$vendor->vendor_id}}" size="lg">Tag & Quotation per Item </a></li>
                        <li><a target="_blank" href="/member/vendor/vendor-details/{{$vendor->vendor_id}}">View Transactions</a></li>
                      </ul>
                    </div>
                    @else
                    <a  class="popup" link="/member/vendor/archived/{{$vendor->vendor_id}}/active" size="md">Make Active</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">NO VENDOR FOUND</td>
            </tr>
            @endif
        </tbody>
    </table>
    <div class="text-center pull-right">
        {!!$_vendor->render()!!}
    </div>