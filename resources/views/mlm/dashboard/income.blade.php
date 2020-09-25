

<div>
  <!-- Widget: user widget style 1 -->
  <div class="box box-widget widget-user-2">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-aqua">
      <div class="widget-user-image">
        <img class="img-circle" src="/assets/mlm/default-pic.png" alt="User Avatar">
      </div>
      <!-- /.widget-user-image -->
      <h3 class="widget-user-username">{{$customer_info->mlm_username}}</h3>
      <h5 class="widget-user-desc">@if(isset($slot_now->membership_name)) {{$slot_now->membership_name}} @else @endif</h5>
    </div>
    <div class="box-footer no-padding">
      <ul class="nav nav-stacked">
        <li><a href="javascript:">Slot No <span class="pull-right badge ">@if(isset($slot_now->slot_no)) {{$slot_now->slot_no}} @else No Slot @endif</span></a></li>
        @if(isset($slot_stairstep))
          <li><a href="javascript:">Stairstep Rank <span class="pull-right badge ">{{$slot_stairstep}}</span></a></li>
        @endif
        <li><a href="javascript:"><hr></li>
        <li><a href="javascript:">Income Summary</li>
        @if(isset($slot_now))
            @if($slot_now != null)
                @if (Session::has('message'))
                    <li><a href="javascript:" class="pull-right badge bg-red">{{ Session::get('message') }}</a></li>
                @endif
                @foreach($plan_settings as $key => $value)
                <li><a href="javascript:">{{$value->marketing_plan_label}} <span class="pull-right badge bg-aqua">{{currency('PHP', $earning[$key])}}</span></a></li>
                @endforeach
                @if($single_leg_income >= 0)
                  <li><a href="javascript:">Single Line Binary Income<span class="pull-right badge bg-aqua">{{currency('PHP', $single_leg_income)}}</span></a></li>
                @endif
                @if(isset($slot_stairstep)) 
                <li><a href="javascript:">Rebates Bonus<span class="pull-right badge bg-aqua">{{currency('PHP', $rebates)}}</span></a></li>
                <li><a href="javascript:">Over-ride Bonus<span class="pull-right badge bg-aqua">{{currency('PHP', $override)}}</span></a></li>
                <li>
                  <a href="javascript:" class="clearfix"><h4>Total<span class="pull-right badge bg-aqua" style="font-size: 15px">{{currency('PHP', array_sum($earning) + $rebates + $override + $single_leg_income)}}</h4></span></a>
                </li>
                @else
                <li>
                  <a href="javascript:" class="clearfix"><h4>Total<span class="pull-right badge bg-aqua" style="font-size: 15px">{{currency('PHP', array_sum($earning) + $single_leg_income)}}</h4></span></a>
                </li>
                @endif
            @else
                <li><a href="javascript:" class="pull-right badge bg-blue">No Active Income yet.</a></li>
            @endif
        @else
        <li><a href="javascript:" class="pull-right badge bg-blue">No Active Income yet.</a></li>
        @endif
        @if($repurchase_cash != null)
        <li><hr></li>
        <li>
           <li><a href="javascript:">WALLET REPURCHASE <span class="pull-right badge bg-aqua">{{currency('PHP', $repurchase_cash)}}</span></a></li>
        </li>
        @endif
        <li><hr></li>
        <li><a href="javascript:">Points Summary</li>
        @if(isset($slot_now))
            @if($slot_now != null)
                @if (Session::has('message'))
                    <li><a href="javascript:" class="pull-right badge bg-red">{{ Session::get('message') }}</a></li>
                @endif
                @foreach($plan_settings_2 as $key => $value)
                <li><a href="javascript:">{{$value->marketing_plan_label}} <span class="pull-right badge bg-aqua">{{$earning_2[$key]}}</span></a></li>
                @endforeach 
                <li class="">
                <?php if(!isset($earning_2)) $earning_2[0] = 0; ?>
                  <a href="javascript:" class="clearfix"><h4>Total<span class="pull-right badge bg-aqua" style="font-size: 15px">{{array_sum($earning_2)}}</h4></span></a>
                </li>
                @if($binary == 1)
                <li><a href="javascript:">Binary Left Points <span class="pull-right badge bg-aqua">{{$left}}</span></a></li>
                <li><a href="javascript:">Binary Right Points <span class="pull-right badge bg-aqua">{{$right}}</span></a></li>
                @endif
            @else
                <li><a href="javascript:" class="pull-right badge bg-blue">No Active Income yet.</a></li>
            @endif
        @else
        <li><a href="javascript:" class="pull-right badge bg-blue">No Active Income yet.</a></li>
        @endif
      </ul>
    </div>
  </div>
  <!-- /.widget-user -->
</div>
