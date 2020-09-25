@extends('member.layout')

@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection

@section('content')
<form action="/member/product/saveCollection" method="POST">
<input type="hidden" name="_token" value="{{csrf_token()}}" id="_token"/>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"><span class="color-gray">Products/Collections</span>/Create collections</span>
                <small>
                Manage your collections
                </small>
            </h1>
            <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Save</button>
            
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default panel-block panel-title-block">
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <i class="fa fa-search pos-absolute location-fa f-16 color-gray" aria-hidden="true"></i>
                                <input type="text" name="" class="form-control indent-15" placeholder="Start typing to search for product..."/>
                                <span class="input-group-btn">
                                    <button class="btn btn-custom-white-gray" data-toggle="modal" data-target="#ProductModal" type="button">Browse product</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-md-12 item-list table-responsive">
                            @if($_item != '')
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default panel-block panel-title-block">
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="">Collection name</label>
                            <input type="text" name="collection_name" class="form-control" value="{{$collection->collection_name}}" placeholder="Collection name" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Notes</label>
                            <textarea class="form-control textarea-expand" name="notes" placeholder="Notes">{!!nl2br($collection->note)!!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div id="ProductModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select products</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
          <div class="form-group">
            <div class="col-md-12">
              <i class="fa fa-search pos-absolute location-fa f-16 color-gray"></i>
              <input type="text" class="form-control indent-15" name="" placeholder="Find products...">
            </div>
          </div>
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
          <div class="search-item-result" style="display: none">
            <div class="form-group">
              <div class="col-md-12">
                <label class="f18 back-list c-pointer"></label>
              </div>
            </div>
            <div class="form-group">
              <form class="item-result" id="item-result">
                
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-custom-white" data-dismiss="modal" type="button">Close</button>
        <button class="btn btn-custom-primary btn-add-collection" type="button" disabled type="button">Add to order</button>
      </div>
    </div>

  </div>
</div>

@endsection

@section('script')
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/collectioncreate.js"></script>
@endsection