@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan</span>
                <small>
                    You can set the computation of your MLM system here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-default pull-right">Recompute Slots</a>
            <a href="javascript:" class="panel-buttons btn btn-default pull-right" onClick="action_load_link_to_modal('/member/mlm/plan/wallet/type/view', 'lg')">Wallet Type</a>
        </div>
    </div>
</div>
<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th class="text-left">Plan Code</th>
                            <th class="text-left">Marketing Plan Name</th>
                            <th class="text-center">Trigger</th>
                            <th class="text-center">Label</th>
                            <th class="text-center">Enabled</th>
                            <th class="text-center">Release Schedule</th>
                            <th class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($mlm_plan))
                        @foreach($mlm_plan as $mlm)
                        <tr>
                            <td class="text-left">{{$mlm->marketing_plan_code}}</td>
                            <td class="text-left">{{$mlm->marketing_plan_name}}</td>
                            <td class="text-center">{{$mlm->marketing_plan_trigger}}</td>
                            <td class="text-center">{{$mlm->marketing_plan_label}}</td>
                            <td class="text-center">
                                <?php 
                                switch( $mlm->marketing_plan_enable )
                                {
                                    case( 1 ):
                                        echo '<a href="" style="color: green;">Active</a>';
                                    break;
                                    case( 2 ):
                                        echo '<a href="" style="color: red;">Inactive</a>';
                                    break;
                                    case( 0 ):
                                        echo '<a href="" style="color: red;">Not Configured</a>';
                                    break;
                                    default:
                                        echo '<a href="" style="color: red;">Not Configured</a>';
                                    break;
                                }
                                ?>
                                
                            </td>
                            <td class="text-center">
                                <?php 
                                switch( $mlm->marketing_plan_release_schedule )
                                {
                                    case( 1 ):
                                        echo 'Instant';
                                    break;
                                    case( 2 ):
                                        echo 'Daily';
                                    break;
                                    case( 0 ):
                                        echo 'Weekly';
                                    break;
                                    default:
                                        echo 'Monthly';
                                    break;
                                }
                                ?>
                            </td>
                            <td class="text-right">
                                <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="/member/mlm/plan/{{$mlm->marketing_plan_code}}">CONFIGURE</a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7"><center>No available MLM Plan</center></td>
                        </tr>
                        @endif
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <form class="global-submit" method="post" action="/member/mlm/plan/save/setting" id="form-basicu-settingu">
                <table class="table">
                    @yield('table_body')
                    @if(isset($plan_settings))
                        @if(!empty($plan_settings))
                        
                            {!! csrf_field() !!}
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <a href="javascript:" class="pull-right panel-buttons btn btn-primary pull-right btn-custom-primary" onClick="save_form_basic()">Update</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                           Enable MLM 
                                    </td>
                                    <td>
                                            Enable Replicated
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="plan_settings_enable_mlm">Enable</label>
                                        <input type="radio" class="" name="plan_settings_enable_mlm" value="1" {{$plan_settings->plan_settings_enable_mlm == 1 ? "checked" : "" }}>
                                        <label for="plan_settings_enable_mlm">Disable</label>
                                        <input type="radio" class="" name="plan_settings_enable_mlm" value="0" {{$plan_settings->plan_settings_enable_mlm == 0 ? "checked" : "" }}>
                                    </td>
                                    <td>
                                        
                                        <label for="plan_settings_enable_mlm">Enable</label>
                                        <input type="radio"  name="plan_settings_enable_replicated" value="1" {{$plan_settings->plan_settings_enable_mlm == 1 ? "checked" : "" }}>
                                        <label for="plan_settings_enable_mlm">Disable</label>
                                        <input type="radio"  name="plan_settings_enable_replicated" value="0" {{$plan_settings->plan_settings_enable_mlm == 0 ? "checked" : "" }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <label for="plan_settings_slot_id_format">Slot # Format</label>
                                        <select class="form-control" name="plan_settings_slot_id_format" onChange="change_format_type(this)">
                                            <option value="0" {{$plan_settings->plan_settings_slot_id_format == 0 ? "selected" : "" }}>Auto</option>
                                            <option value="1" {{$plan_settings->plan_settings_slot_id_format == 1 ? "selected" : "" }}>Format</option>
                                            <option value="2" {{$plan_settings->plan_settings_slot_id_format == 2 ? "selected" : "" }}>Random</option>
                                            <option value="3" {{$plan_settings->plan_settings_slot_id_format == 3 ? "selected" : "" }}>Same as Membership Code</option>
                                        </select>
                                    </td>
                                    <td id="append_td_type">
                                        @if($plan_settings->plan_settings_slot_id_format == 1)
                                                <label for="plan_settings_format">Slot Label (Added before the slot no. E.G. mlm_slot# = mlm_slot#001)</label>
                                                <input class="form-control" type="text" name="plan_settings_format" value="{{$plan_settings->plan_settings_format}}"> 
                                                <label for="plan_settings_prefix_count">PREFIX COUNT (E.G. 3 = 001 to 999, 5 = 00001 to 99999. Automatically increases if exceeded the count)</label>
                                                <input class="form-control" type="number" name="plan_settings_prefix_count" value="{{$plan_settings->plan_settings_prefix_count}}"> 
                                        @elseif($plan_settings->plan_settings_slot_id_format == 0)
                                            <input type="hidden" name="plan_settings_prefix_count" value="{{$plan_settings->plan_settings_prefix_count}}">
                                            <input class="form-control" type="hidden" name="plan_settings_format" value="{{$plan_settings->plan_settings_format}}"> 
                                        @elseif($plan_settings->plan_settings_slot_id_format == 2)
                                            <label for="plan_settings_format">Slot Label (Added before the slot no. E.G. mlm_slot# = mlm_slot#001)</label>
                                            <input type="text" name="plan_settings_format" class="form-control" value="{{$plan_settings->plan_settings_format}}">
                                           <label for="plan_settings_prefix_count">Count (E.G. 2 = 01 to 99, 3 = 001 to 999. Automatically increases if exceeded the count)</label>
                                            <input type="number" name="plan_settings_prefix_count" class="form-control" value="{{$plan_settings->plan_settings_prefix_count}}">     
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Automatic use of product code</label>
                                        <select name="plan_settings_use_item_code" class="form-control">
                                            <option value="0" {{$plan_settings->plan_settings_use_item_code == 0 ? 'selected' : ''}}>Disable</option>
                                            <option value="1" {{$plan_settings->plan_settings_use_item_code == 1 ? 'selected' : ''}}>Enable</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label>Upgrade slot using membership code</label>
                                        <select name="plan_settings_upgrade_slot" class="form-control">
                                            <option value="0" {{$plan_settings->plan_settings_upgrade_slot == 0 ? 'selected' : ''}}>Disable</option>
                                            <option value="1" {{$plan_settings->plan_settings_upgrade_slot == 1 ? 'selected' : ''}}>Enable</option>
                                        </select>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="40"><center>Email</center></td>
                                </tr>
                                <tr>
                                    <td>Default Downline Rule</td>
                                    <td>
                                        <select name="plan_settings_default_downline_rule" class="form-control">
                                            <option value="manual" {{$plan_settings->plan_settings_default_downline_rule == "manual" ? 'selected' : ''}}>Manual Placement</option>
                                            <option value="auto" {{$plan_settings->plan_settings_default_downline_rule == "auto" ? 'selected' : ''}}>Auto Position</option>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>New Generation Plan</td>
                                    <td>
                                        <select name="plan_settings_new_gen_placement" class="form-control">
                                            <option value="1" {{$plan_settings->plan_settings_new_gen_placement == "1" ? 'selected' : ''}}>Enable</option>
                                            <option value="0" {{$plan_settings->plan_settings_new_gen_placement == "0" ? 'selected' : ''}}>Disable</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Membership Code Email</td>
                                    <td>
                                        <select name="plan_settings_email_membership_code" class="form-control">
                                            <option value="0" {{$plan_settings->plan_settings_email_membership_code == 0 ? 'selected' : ''}}>Disable</option>
                                            <option value="1" {{$plan_settings->plan_settings_email_membership_code == 1 ? 'selected' : ''}}>Enable</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Product Code Email</td>
                                    <td>
                                        <select name="plan_settings_email_product_code" class="form-control">
                                            <option value="0" {{$plan_settings->plan_settings_email_product_code == 0 ? 'selected' : ''}}>Disable</option>
                                            <option value="1" {{$plan_settings->plan_settings_email_product_code == 1 ? 'selected' : ''}}>Enable</option>
                                        </select>
                                    </td>
                                </tr>                               
                                <tr>
                                    <td>Placement Required</td>
                                    <td>
                                        <select name="plan_settings_placement_required" class="form-control">
                                            <option value="1" {{$plan_settings->plan_settings_placement_required == 1 ? 'selected' : ''}}>Required</option>
                                            <option value="0" {{$plan_settings->plan_settings_placement_required == 0 ? 'selected' : ''}}>Not Required</option>
                                        </select>
                                    </td>
                                </tr>                        
                                <tr>
                                    <td>Max Slot Per Account <b>(0 = infinite)</b></td>
                                    <td>
                                        <input type="number" class="form-control" name="max_slot_per_account" value="{{$plan_settings->max_slot_per_account}}">
                                    </td>
                                </tr>                    
                                <tr>
                                    <td>Max Slot Per Account <b>(0 = infinite)</b></td>
                                    <td>
                                        <input type="number" class="form-control" name="max_slot_per_account" value="{{$plan_settings->max_slot_per_account}}">
                                    </td>
                                </tr>                  
                                <tr>
                                    <td>Enable Privilege</td>
                                    <td>
                                        <select class="form-control" name="enable_privilege_system">
                                            <option value="0" {{$plan_settings->enable_privilege_system == 0 ? 'selected' : ''}}>Disable</option>
                                            <option value="1" {{$plan_settings->enable_privilege_system == 1 ? 'selected' : ''}}>Enable</option>
                                        </select>
                                    </td>
                                </tr>
                                    <td>Privilege Membership</td>
                                    <td>
                                        <select class="form-control" name="membership_chosen_id">
                                                <option value="0">None</option>
                                            @foreach($membership_list as $list)
                                                <option value="{{$list->membership_id}}" {{$list->membership_privilege == 1 ? 'selected' : ''}}>{{$list->membership_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                </tr>
                                    <td>Restricted Membership</td>
                                    <td>
                                        <select class="form-control" name="membership_restricted_id">
                                                <option value="0">None</option>
                                                @foreach($membership_list as $list)
                                                    <option value="{{$list->membership_id}}" {{$list->membership_restricted == 1 ? 'selected' : ''}}>{{$list->membership_name}}</option>
                                                @endforeach
                                        </select>
                                    </td>
                                </tr>
                                </tr>
                                    <td>Repurchase Cashback Points Convert Day</td>
                                    <td>
                                        <input type="number" class="form-control" name="repurchase_cashback_date_convert" value="{{$plan_settings->repurchase_cashback_date_convert}}">
                                    </td>
                                </tr>
                            </tbody>
                        
                        @else
                        <tr>
                            <td><center>-No Data-</center></td>
                        </tr>  
                        @endif
                    @else
                    <tr>
                        <td><center>Invalid Settings</center></td>
                    </tr>
                    @endif
                </table>
                </form>
            </div>
        </div>
    </div>    
</div>    

    
</div>

@endsection

@section('script')
    <script type="text/javascript">
        var def0 = '<label for="plan_settings_format">Slot Label (Added before the slot no. E.G. mlm_slot# = mlm_slot#001)</label><input class="form-control" type="text" name="plan_settings_format" value=""> <label for="plan_settings_prefix_count">PREFIX COUNT (E.G. 3 = 001 to 999, 5 = 00001 to 999. Automatically increases if exceeded the count)</label><input class="form-control" type="number" name="plan_settings_prefix_count" value="0">'; 
        var def2 = '<label for="plan_settings_format">Slot Label (Added before the slot no. E.G. mlm_slot# = mlm_slot#001)</label><input type="text" name="plan_settings_format" class="form-control" value=""><label for="plan_settings_prefix_count">Count (E.G. 2 = (Random from) 01 to 99, 3 = (Random from) 001 to 999. Automatically increases if exceeded the count)</label><input type="number" name="plan_settings_prefix_count" class="form-control" value="0"> '; 
        var nodef = '<input type="hidden" name="plan_settings_prefix_count" value="0"><input class="form-control" type="hidden" name="plan_settings_format" value="0">';
        function save_form_basic()
        {
            $('#form-basicu-settingu').submit();
        }
        function change_format_type(ito)
        {
            console.log(ito.value);
            var valyu = ito.value
            if(valyu == 1)
            {
                $('#append_td_type').html( def0);
            }
            else if(valyu == 0)
            {
               $('#append_td_type').html(nodef); 
            }
            else if(valyu == 2)
            {
                console.log(def2);
                $('#append_td_type').html(def2); 
            }
        }
        function submit_done(data)
        {
        	if(data.response_status == "warning")
        	{
        		var erross = data.warning_validator;
        		$.each(erross, function(index, value) 
        		{
        		    toastr.error(value);
        		}); 
        	}
        	else if(data.response_status == "success")
        	{
        	    toastr.success('Settings Successfully Editted');
        	}
            else if(data.response_status == 'success_wallet_type')
            {
                toastr.success('Wallet type edit successful!');
                action_load_link_to_modal('/member/mlm/plan/wallet/type/view', 'lg')
            }
    	
        }
    </script>
@endsection
