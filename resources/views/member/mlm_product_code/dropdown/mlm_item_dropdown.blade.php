@if(!empty($_item))
    @if($multiple == "true")
        <select class="form-control item_id item_id_multiple" name="item_id[]">
            @foreach($_item as $item)
            	<option value="{{$item->item_id}}" item_price="{{$item->item_price}}" @if($selected) @if($selected == $item->item_id) selected @endif  @endif >{{$item->item_name}}</option>
        	@endforeach
        </select>    
    @else
        <select class="form-control item_id_multiple" id="name_id" name="item_id">
            @foreach($_item as $item)
            	<option value="{{$item->item_id}}" item_price="{{$item->item_price}}" @if($selected) @if($selected == $item->item_id) selected @endif  @endif >{{$item->item_name}}</option>
        	@endforeach
        </select>
    @endif
@else
No Available Membership.
@endif