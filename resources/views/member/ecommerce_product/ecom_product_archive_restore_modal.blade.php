<form class="global-submit form-horizontal" role="form" action="/member/ecommerce/product/product-archive-restore/{{$product->eprod_id}}" id="archive_item" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="action" value="{{$title}}">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">&times;</button>
    	<h4 class="modal-title">Do you really want to {{$title}} this product?</h4>
    </div>
    <div class="modal-body add_new_package_modal_body clearfix">
        <div class="col-md-12">
            <center><h3>Product Name: {{$product->eprod_name}}</h3></center>
        </div>
      <!--   <div class="col-md-12">
            <div class="col-md-6"><button class="btn btn-def-white btn-custom-white col-md-12" data-dismiss="modal">Cancel</button></div>
            <div class="col-md-6"><button class="btn btn-custom-blue col-md-12 ">Confirm</button></div>
        </div> -->
    </div>
    <div class="modal-footer">
            <div class="col-md-6"><button class="btn btn-def-white btn-custom-white col-md-12" data-dismiss="modal">Cancel</button></div>
            <div class="col-md-6"><button class="btn btn-custom-blue col-md-12 ">Confirm</button></div>
    </div>	
</form>