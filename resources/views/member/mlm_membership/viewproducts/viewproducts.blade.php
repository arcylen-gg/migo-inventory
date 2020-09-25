@if($category == 'all')
  @if($_product)
    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table_product_list">
            <thead>
                <tr>
                    <td class="col-md-1"><input type="checkbox" name="" id="selectAll"></td>
                    <td class="col-md-2"></td>
                    <td class="col-md-3">Product</td>
                    <td class="col-md-3">Inventory</td>
                    <td class="col-md-2">Type</td>
                    <td class="col-md-2">Vendor</td>
                </tr>
            </thead>
            <tbody>
                @foreach($_product as $product)
                <tr>
                    <td><input type="checkbox" name="" product_id="{{$product->product_id}}" product_name="{{$product->product_name}}" variant_id="{{$product->variant_id}}"></td>
                    <td><img src="{{ $product->image_path }}" style="width:50px"></td>
                    <td>
                        <a href="/member/product/edit/{{ $product->product_id }}">
                            {{ $product->product_name }}
                        </a>
                    </td>
                    <td>{{ $product->product_inventory }}</td>
                    <td>{{ $product->type_name }}</td>
                    <td>{{ $product->vendor_name }}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="5"></td>
                  <td><button class="btn btn-custom-primary btn-add-to-product-set">Add to package set</button></td>
                </tr>
            </tbody>
        </table>
    </div>
  @else
  <div class="col-md-12"><center>No Available Products.</center></div>
  @endif
@else
<div class="form-group search-list-option">
    <div class="col-md-12">
      <div class="list-group">
        <a href="#" class="list-group-item search-custom-list" data-content="All products"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>All products</a>
        <a href="#" class="list-group-item search-custom-list" data-content="Popular products"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Popular products</a>
        <!-- <a href="#" class="list-group-item search-custom-list" data-content="Collections"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Collections</a> -->
        <a href="#" class="list-group-item search-custom-list" data-content="Product types"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Product types</a>
        <!-- <a href="#" class="list-group-item search-custom-list" data-content="Tags"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Tags</a> -->
        <a href="#" class="list-group-item search-custom-list" data-content="Vendors"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>Vendors</a>
      </div>
      
    </div>
  </div>  
@endif

<div id="add_new_product_to_package" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Product To Package</h4>
      </div>
      <div class="modal-body add_new_product_to_package_body">
      	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary  btn-custom-primary" type="button">Confirm add package</button>
      </div>
    </div>

  </div>
</div>