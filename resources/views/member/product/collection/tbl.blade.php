<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<table class="table table-hover table-condensed">
  <thead>
    <tr>
      <th></th>
      <th class="text-center">Product variant</th>
      <th class="text-center">SKU</th>
      <th class="text-center">Price</th>
      <th class="text-center">Visibility</th>
      <th></th>
    </tr>
  </thead>

@foreach($_item as $item)
<tr id="tr-collection-{{$item['collection_item_id']}}">
  <td>
    <img src="{{$item['variant_main_image']}}" class="img-50-50"></img>
  </td>
  <td>
    <a href="">{{$item['product_name']}}</a><Br>
    <span class="color-dark-gray">{{$item['variant_name']}}</span>
  </td>
  <td class="text-center">
    {{$item['variant_sku']}}
  </td>
  <td class="text-right">
    <span class="pull-left">₱</span>{{$item['variant_price']}}
  </td>
  <td class="text-center">
    <input type="checkbox" class="visibility-toggle" data-toggle="toggle" data-on="Show" data-off="Hide" data-content="{{$item['collection_item_id']}}" {{$item['hide'] == 0? 'checked':''}}>
  </td>
  <td class="text-center">
    <a href="#" class="remove-collection" data-content="{{$item['collection_item_id']}}"><i class="fa fa-times" aria-hidden="true"></i></a>
  </td>
</tr>
@endforeach
</table>