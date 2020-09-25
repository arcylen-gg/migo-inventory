@extends('member.layout')
@section('css')
@endsection

@section('content')
<form class="form-horizontal global-submit" method="POST" action="/member/ecommerce/settings/banksetting/settingupdate">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <input type="hidden" name="trigger" value="BANK"/>
    <div class="form-group">
        <div class="panel panel-default panel-block panel-title-block">
            <div class="panel-heading">
                <div>
                    <i class="fa fa-cogs"></i>
                    <h1>
                        <span class="page-title"><a class="color-gray" href="/member/ecommerce/settings">Settings</a>/Bank</span>
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
                                <label for="">ENABLED<input type="radio" name="enable" {{$bank->ecommerce_setting_enable == 1 ? 'checked':''}} value="1"/></label>
                            </div>
                       </li> 
                       <li class="display-inline-block margin-left-30">
                           <div class="radio">
                                <label for="">DISABLED<input type="radio" name="enable" {{$bank->ecommerce_setting_enable == 0 ? 'checked':''}} value="0"/></label>
                            </div>
                       </li> 
                    </ul>
                    <button class="btn btn-custom-primary pull-right popup pull-left-20px" link="/member/ecommerce/settings/create_banking" type="button">Add Bank</button>
                </div>
                <hr class="margin-top-0">
                <div class="form-group">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#active-bank"><i class="fa fa-star"></i>&nbsp;Active Bank</a></li>
                          <li><a data-toggle="tab" href="#inactive-bank"><i class="fa fa-trash"></i>&nbsp;Inctive Bank</a></li>
                        </ul>
                        
                        <div class="tab-content">
                          <div id="active-bank" class="tab-pane fade in active">
                            <table class="table table-hover table-bank-active">
                                @foreach($bank_active as $bank_a)
                                <tr id="banking-{{$bank_a->ecommerce_banking_id}}">
                                    <td class="text-left">
                                        <span><a href="javascript:" class="popup" link="/member/ecommerce/settings/loadBankdata/{{$bank_a->ecommerce_banking_id}}">{{$bank_a->ecommerce_banking_name}}</a>
                                        <a href="javascript:" class="pull-right popup" link="/member/ecommerce/settings/archive_warning_bank/{{Crypt::encrypt($bank_a->ecommerce_banking_id)}}"><i class="fa fa-times"></i></a></span>
                                    </td>
                                </tr>
                                @endforeach
                            </table> 
                          </div>
                          <div id="inactive-bank" class="tab-pane fade">
                            <table class="table table-hover table-bank-inactive">
                                @foreach($bank_inactive as $bank_i)
                                <tr id="inactive-banking-{{$bank_i->ecommerce_banking_id}}">
                                    <td class="text-left">
                                        <span><a href="javascript:" class="popup" link="/member/ecommerce/settings/loadBankdata/{{$bank_i->ecommerce_banking_id}}">{{$bank_i->ecommerce_banking_name}}</a>
                                        <a href="javascript:" class="pull-right popup" link="/member/ecommerce/settings/restore_warning_bank/{{Crypt::encrypt($bank_i->ecommerce_banking_id)}}"><i class="fa fa-times"></i></a></span>
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