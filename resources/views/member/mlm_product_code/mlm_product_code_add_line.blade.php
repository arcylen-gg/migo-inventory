<form class="global-submit" action="/member/mlm/product_code/sell/add_line/submit" method="post">
    {!! csrf_field() !!}
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Add Products</h4>
  </div>
  <div class="modal-body clearfix modallarge-body-layout background-white" style="z-index: 999 !important;">
      <div class="col-md-12">
        <label>Item:</label>
        <select class="drop-down-item" name="item_id">
            @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
        </select>
      </div>
      <div class="col-md-6 hide">
          <label for="item_id">Item</label>
         $item_list 
      </div>
      <div class="col-md-12">
          <label for="quantity">Quantity:</label>
          <input type="number" class="form-control quantity_e" name="quantity" value="1" onchange="get_price()">
      </div>
      <div class="col-md-6 hide" id="price_id_a">
        <label for="quantity">Original Price</label>
        <input type="number" class="form-control original_price_item" value="0" readonly>
      </div>
      <div class="col-md-6 hide" id="price_id_b">
        <label for="quantity">Discounted Price</label>
        <input type="number" class="form-control discounted_price_item" value="0" readonly>
      </div>
      <div class="col-md-12"><br></div>
      <div class="col-md-12" id="add-new-line-warning"></div>
  </div>
  <div class="modal-footer">
    
    <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
    <button class="btn btn-primary">Submit</button>
  </div>
  <input type="hidden" class="slot_id_as" name="slot_id" value="{{$slot_id}}">
</form>

<div class="load_price hide"></div>
<script type="text/javascript">

function get_price()
{
  $('#price_id_a').html('<center><div style="margin: 20px auto;" class="loader-16-gray"></div></center>');
  $('#price_id_b').html('<center><div style="margin: 20px auto;" class="loader-16-gray"></div></center>');
  var item_id = $('.item_id_multiple').val(); 
  var quantity = $('.quantity_e').val();
  $('.load_price').load('/member/mlm/product/discount/get/' + item_id, function(data){
    var html = '<label for="quantity">Original Price</label>'
        html += '<input type="number" class="form-control original_price_item" value="'+data+'" readonly>';
    $('#price_id_a').html(html);
    $('.original_price_item').val(data * quantity);
    get_discounted_price(item_id);
    // 
  });
}
function get_discounted_price(item_id) 
{
  return false;
  var slot_id = $('.slot_id_as').val();
  var quantity = $('.quantity_e').val();
  $('#price_id_b').html('<center><div style="margin: 20px auto;" class="loader-16-gray"></div></center>');
  $('.load_price').load('/member/mlm/product/discount/get/'+item_id+'/' + slot_id, function(data)
  {
    var html = '<label for="quantity">Discounted Price</label>';
        html += '<input type="number" class="form-control discounted_price_item" value="'+data+'" readonly>';
    $('#price_id_b').html(html);
    $('.discounted_price_item').val(data * quantity);
  });
}
$('.item_id_multiple').on('change', function(){
  get_price();
});
$(".drop-down-item").globalDropList(
{
    link: '/member/item/add',
    link_size: 'lg',
    maxHeight: "309px",
    width: '100%',
    placeholder: 'Item'
});
</script>