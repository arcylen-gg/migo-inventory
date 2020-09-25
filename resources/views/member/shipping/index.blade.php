@extends('member.layout')
@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
@endsection

@section('content')
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-truck"></i>
            <h1>
                <span class="page-title">Shipping</span>
                <small>
                Add your shipping option
                </small>
            </h1>
            
            <a href="#" class="panel-buttons btn btn-custom-blue pull-right btn-create-modal" data-toggle="modal" data-target="#ShippingModal">Create Shipping</a>
        </div>
    </div>
    
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body table-responsive">
        <table class="table table-hover table-bordered table-condensed">
            <thead>
                <th class="text-center">Shipping Name</th>
                <th class="text-center">Contact</th>
                <th class="text-center">Shipping Fee</th>
               
            </thead>
            <tbody class="tbl-shipping">
                @foreach($_shipping as $ship)
                <tr data-toggle="modal" data-target="#ShippingModal" class="shipping-click" data-content="{{$ship->shipping_id}}">
                    <td>
                        <a href="#" data-toggle="modal" data-target="#ShippingModal" class="shipping-click" data-content="{{$ship->shipping_id}}">{{$ship->shipping_name}}</a>
                    </td>
                    <td>{{$ship->contact}}</td>
                    <td class="text-center">{{$ship->currency}}{{number_format($ship->shipping_fee,2)}}/{{$ship->unit.' '.$ship->measurement}}</td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="ShippingModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-create">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-md-4">Shipping name</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="shipping_name" placeholder="Shipping Name" name=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-4">Shipping contact</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="shipping_contact" placeholder="Shipping Contact" name=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-4">Unit of Measurement</label>
                    <div class="col-md-8 ">
                        <div class="input-group width-100">
                            <span class="input-group-btn width-21">
                                <select class="form-control" id="measurement">
                                    <option value="kg">kg</option>
                                    <option value="lbs">lbs</option>
                                </select>
                            </span>
                            <input type="number" placeholder="0" id="unit" value="1" class="form-control text-right">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-4">Amount per Unit</label>
                    <div class="col-md-8">
                        <div class="input-group width-100">
                            <span class="input-group-btn width-21">
                                <select class="form-control selectpicker" id="currency">
                                    <option value="PHP">PHP</option>
                                    <option value="USD">USD</option>
                                </select>
                            </span>
                            <input type="number" placeholder="0" id="fee" step="any" class="form-control text-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-update"></div>
      </div>
     <div class="modal-footer">
         <button class="btn btn-custom-red btn-del-modal">Remove</button>
        <button class="btn btn-custom-blue btn-update-modal">Update</button>
        <button class="btn btn-custom-blue btn-create">Create</button>
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/shipping.js"></script>
@endsection