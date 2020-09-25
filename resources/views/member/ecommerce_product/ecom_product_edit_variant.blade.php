@extends('member.layout')
@section('content')
<form class="form-to-submit global-submit" action="/member/ecommerce/product/update-variant" method="post">
    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" class="" name="product_id" id="product_id" value="{{ $product->eprod_id }}">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Products / {{ $product->eprod_id }} / <text id="variant_name"></text> &raquo; Edit</span>
                    <small>
                    Edit product variants
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Save</button>
                <a href="" class="panel-buttons btn btn-danger pull-right {{isset($variant_id) ? '' : 'hidden'}}" id="delete_variant">Delete Variant</a>
                <a href="/member/ecommerce/product/edit/{{ $product->eprod_id }}" class="panel-buttons btn btn-default pull-right">Cancel</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <!-- FORM.TITLE -->
            <div class="form-box-divider">
                <div class="row">
                    <div class="col-md-9">
                        <div class="fieldset">
                            <!-- title -->
                            <!-- <label>Product Code</label>
                            <div class="title">{{ $product->eprod_id }}</div> -->
                            <div>{{ $_variant->count() }} variants</div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- FORM.VARIANTS -->
            <div class="form-box-divider">
                @foreach($_variant as $key=>$variants)
                    <div class="variant-item variant-nav-container" id="{{ $variants->evariant_id }}">
                        <div class="variant-nav-list">
                            <div class="image-container">
                                <img src="{{ $variants->image_path }}">
                            </div>
                            <div class="text">{{ $variants->variant_name }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        </div>
        <div class="col-md-8">
            <div class="form-box-divider light variant-info-container">
                @include('member.ecommerce_product.ecom_edit_variant',['default' => '1']);
            </div>
        </div>
    </div>
</form>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/product.css">
<link rel="stylesheet" type="text/css" href="/assets/external/selectize.js/dist/css/selectize.default.css">
@endsection
        
@section('script')
<script type="text/javascript">
    var global_variant_id   = "{{ Request::input('variant_id') }}";
    var product_id          = {{ $product->eprod_id }};
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
</script>
<script type="text/javascript" src="/assets/member/js/evariant.js"></script>
<script type="text/javascript" src="/assets/external/selectize.js/dist/js/standalone/selectize.js"></script>
@endsection