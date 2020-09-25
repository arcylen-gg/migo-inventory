@extends('member.layout')

@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection

@section('content')
<input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"><span class="color-gray">Products</span>/Collections</span>
                <small>
                Manage your collections
                </small>
            </h1>
            <a href="/member/product/createcollection" class="panel-buttons btn btn-custom-primary pull-right">Create</a>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-custom-white-gray btn-filter">Filter collections</button>
                        </span>
                        <span class="pos-absolute f-16 color-gray margin-5-5"><i class="fa fa-search" aria-hidden="true"></i></span>
                        <input type="text"  name="" class="form-control indent-13" placeholder="Start typing to search for products...">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover table-condensed">
                        <tr>
                            <thead>
                                <th>Collection name</th>
                                <th  class="text-center">Notes</th>
                                <th class="text-center">Product quantity</th>
                                <th class="text-center">Visibility</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($_collection as $collection)
                                <tr>
                                    <td>
                                        <a href="/member/product/collection/edit/{{Crypt::encrypt($collection->collection_id)}}">{{$collection->collection_name}}</a>
                                    </td>
                                    <td>
                                        {{nl2br($collection->note)}}
                                    </td>
                                    <td class="text-center">
                                        {{$collection->totalItem}}
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="visibility-toggle" data-toggle="toggle" data-on="Show" data-off="Hide" data-content="{{$collection->collection_id}}" {{$collection->hide == 0 ? 'checked':''}}>
                                    </td>
                                    <td>
                                        <a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="/assets/member/js/collection.js"></script>
@endsection