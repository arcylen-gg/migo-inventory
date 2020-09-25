@if(isset($_choose_item))
	@if(count($_choose_item) > 0)
		<label class="hidden" {{$total_cost = 0}} {{$total_price = 0}} {{$total_qty = 0}}></label>
		@foreach($_choose_item as $item)
		<tr>
		    <td class="text-center">{{$item['item_sku']}}</td>
		    <td class="text-center" {{$total_cost += $item['item_cost']}}>{{currency('PHP', $item['item_cost'])}}</td>
		    <td class="text-center" {{$total_price += $item['item_price']}}>{{currency('PHP', $item['item_price'])}}</td>
		    <td class="text-center"{{$total_qty += $item['quantity']}}>{{number_format($item['quantity'])}}</td>
		    <td class="text-center" style="color:red;cursor: pointer;" onClick='remove_item({{$item["item_id"]}})' ><i class='fa fa-times loading-spinner-{{$item["item_id"]}}'></i></td>
		</tr>
		@endforeach
		<tr>
			<td class="text-center"><strong>TOTAL</strong></td>
			<td class="text-center"><strong>{{currency('PHP ',$total_cost)}}</strong></td>
			<td class="text-center"><strong>{{currency('PHP ',$total_price)}}</strong></td>
			<td class="text-center"><strong>{{number_format($total_qty)}}</strong></td>
			<td></td>
		</tr>
	@else
	<tr>
		<td colspan="5" class="text-center">NO ITEM YET</td>
	</tr>
	@endif
@else
	<tr>
		<td colspan="5" class="text-center">NO ITEM YET</td>
	</tr>
@endif