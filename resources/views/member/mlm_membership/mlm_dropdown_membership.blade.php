@if(!empty($membership_packge))
    @if($multiple == "true")
        <select class="form-control membership_package_id" name="membership_package[]">
            @foreach($membership_packge as $mem_pack)
            	<option value="{{$mem_pack->membership_package_id}}" mem_price="{{$mem_pack->membership_price}}" @if($selected) @if($selected == $mem_pack->membership_package_id) selected @endif  @endif >{{$mem_pack->membership_package_name}}</option>
        	@endforeach
        </select>    
    @else
        <select class="form-control" name="membership_package">
            @foreach($membership_packge as $mem_pack)
            	<option value="{{$mem_pack->membership_package_id}}" @if($selected) @if($selected == $mem_pack->membership_package_id) selected @endif  @endif >{{$mem_pack->membership_package_name}}</option>
        	@endforeach
        </select>
    @endif
@else
No Available Membership.
@endif