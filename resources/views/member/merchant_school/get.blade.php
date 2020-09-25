@foreach($items as $key => $item)
<tr>
	<td>{{$item->item_name}}</td>
	<td>
	<form class="global-submit" method="post" action="/member/mlm/merchant_school/destroy">
		<input type="hidden" name="item_id" value="{{$item->item_id}}">
		{!! csrf_field() !!}
		<button class="btn btn-warning">✕</button>
	</form>
	</td>
</tr>

@endforeach