<ul class="list-group-item c-pointer">
  @foreach($_item as $item)
  <li class="list-flex cusom-drop-item item-list-search" data-value="{{$item->variant_id}}">
    <img src="{{$item->image_path}}" class="img-35-35">
      <ul class="margin-nl-20">
        <li>
          <span>{{$item->product_name}}</span>
        </li>
        <li>
          <span class="color-gray">{{$item->variant_name}}</span>
        </li>
      </ul>
      <hr>
  </li>
  
  @endforeach
</ul>