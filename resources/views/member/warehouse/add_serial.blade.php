  <div class="modal-header">
    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
    <h4 class="modal-title layout-modallarge-title item_title">Add Serial Number</h4>
  </div>
<form id="modal_form_large" class="global-submit form_one"  enctype="multipart/form-data" action="/member/item/add_serial_number_submit" method="post">
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">

    <div class="panel-body form-horizontal">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-horizontal">
            @if($items)
                @foreach($items as $item)
                <div class="form-group">
                    <div class="col-md-12">
                        <h4><strong>{{$item->item_name}}</strong></h4>
                        <h4>Quantity: {{$item->quantity}}</h4>
                        <input type="hidden" name="inventory_id[{{$item->item_id}}]" value="{{$item->inventory_id}}">
                        <input type="hidden" name="quantity[{{$item->item_id}}]" value="{{$item->quantity}}">
                    </div>
                    <div class="col-md-12 text-center"><h5>Serial Number:</h5></div>
                    <?php 
                    for ($i = 0, $num = 1; $i < $item->quantity; $i++,$num++)
                    {
                        echo "<div class='form-group'><div class='col-md-2 text-center'>".$num."</div> 
                              <div class='col-md-10'>
                              <input type='hidden' name='item_id[]' value=".$item->item_id." class='form-control'>
                              <input type='text' name='serial[]' class='form-control' order_number=".$num.">
                              </div>
                              </div>";
                    }
                    ?>
                </div>
                @endforeach
            @endif
        </div>
    </div>
  </div>
 <div class="modal-footer" >
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Add Serial Number</button>
</div>
</form>

<script type="text/javascript" src="/assets/member/js/warehouse.js"></script>
<script type="text/javascript">

    var $myDiv = $("input[order_number='1']");
    if ( $myDiv.length)
    { 
       $($myDiv).focus();
    }
   $(".serial_container").on("keypress","input[name='serial[]']",function(e)
   {
      if(e.keyCode == 13)
      { 
        var order_number = $(this).attr("order_number");
            order_number = parseInt(order_number) + 1;
        var $myDiv = $("input[order_number='"+order_number+"']");
        if ( $myDiv.length)
        { 
           $($myDiv).focus();
           e.preventDefault();
           return false;
        }
      }
   });
</script>