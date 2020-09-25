<tr class="{{$class}}">
	<td>
		<span {!!$margin_left!!}>{!!$category!!}</span>
	</td>
	<td class="text-center">
		{{$cat->type_category}}
	</td>
	<td class="text-center">
		<div class="btn-group">
          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-custom">
          	@if($cat->archived != 1)
            <li><a href="javascript:" link="/member/item/category/edit_category/{{$cat->type_id}}" class="popup">Edit Category</a></li>
            <li><a link="/member/item/category/archived/{{$cat->type_id}}/archived" size="md" class="popup" >Archived Category</a></li>
            @else
            <li><a link="/member/item/category/archived/{{$cat->type_id}}/restore" size="md" class="popup" >Restore Category</a></li>
            @endif
          </ul>
        </div>
	</td>
</tr>