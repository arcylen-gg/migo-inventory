@extends('member.layout')
@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
@endsection

@section('content')
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
<div class="form-horizontal">
    <div class="panel panel-default panel-block panel-title-block">
        <div class="panel-heading">
            <div>
                <i class="fa fa-shopping-bag"></i>
                <h1>
                    <span class="page-title"><span class="color-gray">My Store</span>/Contact Information</span>
                    <small>
                    Add your contact information
                    </small>
                </h1>
                
                <!--<a href="#" class="panel-buttons btn btn-custom-blue pull-right btn-create-modal" data-toggle="modal" data-target="#ShippingModal">Create Shipping</a>-->
            </div>
        </div>
        
    </div>


  
    
    <div class="col-md-4">
       <div class="form-group">
           <div class="col-md-12 well well-white border-radius-0">
               <div class="form-group">
                   <div class="col-md-12">
                       <span><u>Create your contact here.</u></span>
                   </div>
               </div>
               
               <div class="form-group">
                   <label for="" class="col-md-2">Contact</label>
                   <div class="col-md-10">
                       <div class="input-group width-100">
                            <span class="input-group-btn width-30">
                                <select class="form-control" id="category">
                                    <option value="email">email</option>
                                    <option value="number">number</option>
                                </select>
                            </span>
                            <input type="text"  id="new_contact" placeholder="Your contact here" class="form-control">
                        </div>
                   </div>
               </div>
               <div class="form-group">
                   <div class="col-md-12">
                       <button class="btn btn-custom-blue pull-right btn-create">Create</button>
                   </div>
               </div>
           </div>
       </div>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-7">
       <div class="form-group">
           <div class="col-md-12 well well-white border-radius-0">
               <div class="form-group">
                   <div class="col-md-12 table-responsive">
                       <table class="table table-hover table-bordered table-condensed">
                           <thead>
                               <th class="text-center">Contact</th>
                               <th class="text-center">Category</th>
                               <th class="text-center">Display</th>
                           </thead>
                           <tbody class="tbl-contact">
                               @foreach($_contact as $contact)
                               <tr>
                                   <td class="text-center">
                                       <a href="#" class="edit-contact" data-content="{{$contact->contact_id}}" data-toggle="modal" data-target="#ContactModal">{{$contact->contact}}</a>
                                   </td>
                                   <td class="text-center">
                                       {{$contact->category}}
                                   </td>
                                   <td class="text-center">
                                       <input type="checkbox" class="checkboxDisplay" {{$contact->primary == 1?'checked="checked"':''}} data-content="{{$contact->contact_id}}">
                                   </td>
                               </tr>
                               @endforeach
                           </tbody>
                       </table>
                   </div>
                   
               </div>
           </div>
       </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <div class="col-md-12 well well-white border-radius-0">
                <div class="form-group">
                   <div class="col-md-12">
                       <span><u>Add your location here.</u></span>
                   </div>
               </div>
               <div class="form-group">
                   <label for=""  class="col-md-2">Location</label>
                   <div class="col-md-10">
                       <i class="fa fa-map-marker pos-absolute f-16 color-gray location-fa" aria-hidden="true"></i>
                       <input type="text" class="form-control indent-13" id="new_location" placeholder="Location">
                   </div>
               </div>
               <div class="form-group">
                   <div class="col-md-12">
                       <button class="btn btn-custom-blue pull-right set-location">Create</button>
                   </div>
               </div>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-7">
        <div class="form-group">
            <div class="col-md-12 well well-white border-radius-0">
                <div class="form-group">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-bordered table-condensed">
                            <thead>
                                <th class="text-center">Location</th>
                                <th class="text-center">Set Primary</th>
                            </thead>
                            <tbody class="tbl-location">
                                @foreach($_location as $location)
                                <tr>
                                    <td><a href="#" class="edit-location" data-toggle="modal" data-target="#ContactModal" data-content="{{$location->location_id}}">{{$location->location}}</a></td>
                                    <td class="text-center">
                                        <input type="radio" name="locatio_primary" class="radio_location" value="{{$location->location_id}}" {{$location->primary == 1?'checked="checkded"':''}}>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="ContactModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <!--<div class="modal-create">-->
        <!--    <div class="form-horizontal">-->
        <!--        <div class="form-group">-->
        <!--            <label for="" class="col-md-4">Contact</label>-->
        <!--            <div class="col-md-8">-->
        <!--                <div class="input-group width-100">-->
        <!--                    <span class="input-group-btn width-21">-->
        <!--                        <select class="form-control" id="measurement">-->
        <!--                            <option value="email">email</option>-->
        <!--                            <option value="number">number</option>-->
        <!--                        </select>-->
        <!--                    </span>-->
        <!--                    <input type="text"  id="contact_new" class="form-control text-right">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="modal-update"></div>
      </div>
     <div class="modal-footer">
         <button class="btn btn-custom-red btn-del-modal">Remove</button>
        <button class="btn btn-custom-blue btn-update-modal">Update</button>
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/contact.js"></script>
@endsection
