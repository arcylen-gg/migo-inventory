<div class="col-md-12">
  <div class="list-group">
  	@foreach($_type as $type)
    <a href="#" class="list-group-item search-sub-custom-list" data-content="{{$type->vendor_name}}" data-value="{{$type->vendor_id}}" data-tirgger="{{$trigger}}"><span class="badge"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>{{$type->vendor_name}}</a>
    @endforeach
  </div>
</div>