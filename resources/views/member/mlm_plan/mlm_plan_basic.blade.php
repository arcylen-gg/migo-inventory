@if(isset($plan))
@if(!empty($plan))
<form class="global-submit" method="post" action="/member/mlm/plan/edit/submit" id="basic_settings_form">
{!! csrf_field() !!}
<input type="hidden" value="{{$plan->marketing_plan_code}}" name="marketing_plan_code" id="marketing_plan_code">
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive" >
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <td>
                                <label class="radio-inline">
                                    <input type="radio" name="marketing_plan_enable" value="1" {{$plan->marketing_plan_enable == 1 ? "checked" : ""}}>Enable
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" name="marketing_plan_enable" value="2" {{$plan->marketing_plan_enable == 2 ? "checked" : ""}}> Disable
                                </label>
                                @if($plan->marketing_plan_enable == 0)
                                <label class="radio-inline">
                                    <input type="radio" name="marketing_plan_enable" value="0" checked>Not Configured
                                </label>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="marketing_plan_label">Label</label>
                                <input type="text" name="marketing_plan_label" value="{{$plan->marketing_plan_label}}" class="form-control" placeholder="Input label for binary">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="marketing_plan_trigger">Trigger</label>
                                {!! mlm_plan_triger($plan->marketing_plan_trigger) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-md-12">
                                   <div class="col-md-3">
                                        <label for="marketing_plan_release_schedule">Release Schedule</label>
                                        {!! mlm_plan_release_schedule($plan->marketing_plan_release_schedule) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <label for="hours">Time(Hour)</label>
                                        <span class="hours">{!! mlm_plan_release_schedule_hour($plan->marketing_plan_release_time) !!}</span>
                                    </div> 
                                    <div class="col-md-3">
                                        <label for="week_days">Days(Weekly)</label>
                                        <span class="week_days">{!! mlm_plan_release_schedule_day($plan->marketing_plan_release_weekly) !!}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="month_day">Days(Monthly)</label>
                                        <span class="month_day">{!! mlm_plan_release_schedule_day_month($plan->marketing_plan_release_monthly) !!}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <!-- <td>
                                <center>Wallet</center>
                                <div class="col-md-6">
                                    <input type="hidden" name="marketing_plan_enable_encash" value="0">
                                    <label class="checkbox-inline pull-right"><input type="checkbox" name="marketing_plan_enable_encash" value="1" {{$plan->marketing_plan_enable_encash == 1 ? 'checked' : ''}}>Enable In Enchasment</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" name="marketing_plan_enable_product_repurchase" value="0">
                                    <label class="checkbox-inline"><input type="checkbox" name="marketing_plan_enable_product_repurchase" value="1" {{$plan->marketing_plan_enable_product_repurchase == 1 ? 'checked' : ''}}>Enable In Product Repurchase</label>
                                </div>
                            </td> -->
                        </tr>
                    </thead>
                </table>
            </div>    
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
    var hours = "{!! mlm_plan_release_schedule_hour($plan->marketing_plan_release_time) !!}";
    var week_days = "{!! mlm_plan_release_schedule_day($plan->marketing_plan_release_weekly) !!}"; 
    var month_day = "{!! mlm_plan_release_schedule_day_month($plan->marketing_plan_release_monthly) !!}";
    
    var hours_no_active = "{!! mlm_plan_release_schedule_hour() !!}";
    var week_days_no_active = "{!! mlm_plan_release_schedule_day() !!}"; 
    var month_day_no_active = "{!! mlm_plan_release_schedule_day_month() !!}";
    
    var hours_null = "<input type='text' class='form-control' name='hours' disabled>";
    var week_null = "<input type='text' class='form-control' name='week_days' disabled>"; 
    var month_null = "<input type='text' class='form-control' name='month_day' disabled>";
    
    time_changer($('.marketing_plan_release_schedule'));
    function time_changer()
    {
        var sel = marketing_plan_release_schedule = $('.marketing_plan_release_schedule');
        var marketing_plan_release_schedule = sel.val();
        
        // 1 = instant
        // 2 = daily
        // 3 = weekly
        // 4= monthly
        if(marketing_plan_release_schedule == 1)
        {
            $('.hours').html(hours_null);
            $('.week_days').html(week_null);
            $('.month_day').html(month_null);
        }
        else if(marketing_plan_release_schedule == 2)
        {
            $('.hours').html(hours);
            $('.week_days').html(week_null);
            $('.month_day').html(month_null);
        }
        else if(marketing_plan_release_schedule == 3)
        {
            $('.hours').html(hours);
            $('.week_days').html(week_days);
            $('.month_day').html(month_null);
        }
        else if(marketing_plan_release_schedule == 4)
        {
            $('.hours').html(hours);
            $('.week_days').html(week_null);
            $('.month_day').html(month_day);
        }
    }
    function submit_done(data)
    {
        if(typeof load_settings == 'function')
        {
            load_settings();
        }

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
    	    var pcode = $('#marketing_plan_code').val();
    	    $('.bsettings').html('<center><div style="margin: 100px auto;" class="loader-16-gray"></div></center>');
    	    $('.bsettings').load('/member/mlm/plan/'+pcode+'/basicsettings');
    	    console.log('/member/mlm/plan/'+pcode+'/basicsettings');
    		toastr.success("Edit Successful");
    	}
    	else if(data.response_status == "successd")
    	{
    		toastr.success("Edit Successful");
    	}
    	else if(data.response_status == "success_add_stairstep")
    	{
    	   load_stair();
    	   toastr.success("Rank Successful Added!");
    	}
    	else if(data.response_status == "success_edit_stairstep")
    	{
    	   load_stair();
    	   toastr.success("Rank Edit Successful!");
    	}
    	else if(data.response_status == "success_submit_pairing_bonus")
    	{
    	    toastr.success("Binary Pairing Edit Successful!");
    	}
    	else if(data.response_status == "success_unilevel_settings")
    	{
    	    load_unilevel_body();
    	    toastr.success("Unilevel Edit Successful!");
    	}
        else if(data.response_status == "success_binary_advance_settings")
        {
            toastr.success("Advanced binary settings Updated");
        }
        else if(data.response_status == "success_matching")
        {
            toastr.success('Matching Settings Set');
        }
        else if(data.response_status == "success_edit_executive")
        {
            toastr.success('Executive Settings Set');
        }
        else if(data.response_status == "success_leadership")
        {
            toastr.success('Success!');
        }
    }
    function update_basic_settings()
    {
        $('#basic_settings_form').submit();
    }
</script>
@endif

@else
-Invalid Plan Code-
@endif