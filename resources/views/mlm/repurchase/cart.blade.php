<div class="col-md-12">
    <div class="panel panel-default panel-block">
        <div class="list-group">
            <div class="list-group-item" id="responsive-bordered-table">
                <div class="form-group">
                Cart
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Subtotal</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tbody class="cart_body">
                                <?php 
                                $subtotal = 0;
                                $total = 0;
                                $discount = 0;
                                ?>
                                @if($cart != null)
                                  @foreach($cart as $key => $value)
                                   <tr>
                                       <td>{{$value['item_info']->item_name}}
                                       <form id="cart_id_{{$key}}"class="global-submit" action="/mlm/repurchase/remove/cart" method="post">
                                         {!! csrf_field() !!}
                                         <input type="hidden" name="item_id" value="{{$key}}">
                                       </form>
                                       </td>
                                       <td>{{$value['quantity']}}</td>
                                       <?php 
                                        $discount += $value['item_discount'] * $value['quantity'];
                                        $total += $value['item_price_subtotal'];
                                        $subtotal += $value['item_price_single'] * $value['quantity'];
                                       ?>
                                       <td>{{$value['item_price_single']}}</td>
                                       <td>{{$value['item_discount']}}</td>
                                       <td>{{$value['item_price_subtotal']}}</td>
                                       <td><button class="btn btn-danger pull-right" onclick="submit_form_item({{$key}})">x</button></td>
                                   </tr>
                                  @endforeach 
                                @endif 
                                  <tr>
                                       <td colspan="6">
                                            <div class="col-md-12">
                                               <span class="pull-right">
                                                   Subtotal - {{$subtotal}}
                                               </span>
                                           </div>
                                       </td>
                                   </tr>   
                                   <tr>
                                       <td colspan="6">
                                            <div class="col-md-12">
                                               <span class="pull-right">
                                                   Discount - {{$discount}}
                                               </span>
                                           </div>
                                       </td>
                                   </tr> 
                                   <tr>
                                       <td colspan="6">
                                            <div class="col-md-12">
                                               <span class="pull-right">
                                                   Total - {{$total}}
                                               </span>
                                            </div>   
                                       </td>
                                   </tr> 
                                   <tr>
                                     <td colspan="6">
                                       <div class="col-md-12">
                                         <span class="pull-right">
                                           Current Wallet - {{$wallet_sum}}
                                         </span>
                                       </div>
                                     </td>
                                   </tr>
                                   <tr>
                                       <td colspan="6">
                                           <div class="col-md-12">
                                               <span class="pull-right">
                                               <form action="/mlm/repurchase/cart/checkout" method="post">
                                               {!! csrf_field() !!}
                                                   <button class="btn btn-primary"><i class="icon-shopping-cart"></i> Checkout</button>
                                               </span>
                                            </div>  
                                       </td>
                                   </tr> 
                                   

                                </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>