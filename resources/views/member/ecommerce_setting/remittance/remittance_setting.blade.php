@extends('member.layout')
@section('css')
@endsection

@section('content')
<form class="form-horizontal global-submit" method="POST" action="/member/ecommerce/settings/banksetting/settingupdate">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <input type="hidden" name="trigger" value="REMITTANCE"/>
    <div class="form-group">
        <div class="panel panel-default panel-block panel-title-block">
            <div class="panel-heading">
                <div>
                    <i class="fa fa-cogs"></i>
                    <h1>
                        <span class="page-title"><a class="color-gray" href="/member/ecommerce/settings">Settings</a>/Remittance</span>
                        <small>
                        Manage your paypal settings
                        </small>
                    </h1>
                   	<button class="btn btn-custom-primary pull-right btn-save-draft margin-left-10">Update</button>
                   	<a href="/member/ecommerce/settings" class="btn btn-custom-white pull-right">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="panel panel-default panel-block background-white">
            <div class="panel-body">
                <div class="form-group margin-bottom-0">
                    <ul class="display-inline-block">
                       <li class="display-inline-block">
                           <div class="radio">
                                <label for="">ENABLED<input type="radio" name="enable" {{$remittance->ecommerce_setting_enable == 1 ? 'checked':''}} value="1"/></label>
                            </div>
                       </li> 
                       <li class="display-inline-block margin-left-30">
                           <div class="radio">
                                <label for="">DISABLED<input type="radio" name="enable" {{$remittance->ecommerce_setting_enable == 0 ? 'checked':''}} value="0"/></label>
                            </div>
                       </li> 
                    </ul>
                    <button class="btn btn-custom-primary pull-right pull-left-20px popup" link="/member/ecommerce/settings/create_remittance" type="button">Add Remittance</button>
                </div>
                <hr class="margin-top-0">
                <div class="form-group">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#active-remittance"><i class="fa fa-star"></i>&nbsp;Active Bank</a></li>
                          <li><a data-toggle="tab" href="#inactive-remittance"><i class="fa fa-trash"></i>&nbsp;Inctive Bank</a></li>
                        </ul>
                        
                        <div class="tab-content">
                          <div id="active-remittance" class="tab-pane fade in active">
                            <table class="table table-hover table-remittance-active">
                               @foreach($remittance_active as $remittance)
                                <tr id="remittance-{{$remittance->ecommerce_remittance_id}}">
                                    <td class="text-left">
                                        <span><a href="javascript:" link="/member/ecommerce/settings/loadRemittancedata/{{$remittance->ecommerce_remittance_id}}" class="popup">{{$remittance->ecommerce_remittance_name}}</a>
                                        <a href="javascript:" class="pull-right popup" link="/member/ecommerce/settings/archive_warning_remittance/{{$remittance->ecommerce_remittance_id}}"><i  class="fa fa-times" aria-hidden="true"></i></a></span>
                                    </td>
                                </tr>
                               @endforeach
                            </table>
                          </div>
                          <div id="inactive-remittance" class="tab-pane fade">
                            <table class="table table-hover table-remittance-inactive">
                                @foreach($remittance_inactive as $remittance_in)
                                <tr id="in-remittance-{{$remittance_in->ecommerce_remittance_id}}">
                                    <td class="text-left">
                                        <span><a href="javascript:" link="/member/ecommerce/settings/loadRemittancedata/{{$remittance_in->ecommerce_remittance_id}}" class="popup">{{$remittance_in->ecommerce_remittance_name}}</a>
                                        <a href="javascript:" class="pull-right popup" link="/member/ecommerce/settings/restore_warning_remittance/{{$remittance_in->ecommerce_remittance_id}}"><i  class="fa fa-times" aria-hidden="true"></i></a></span>
                                    </td>
                                </tr>
                               @endforeach
                            </table>
                          </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/e_commerce/setting.js"></script>
@endsection