<div class="col-md-12">
    <!-- Widget: user widget style 1 -->
    <div class="box box-widget widget-user">
      <!-- Add the bg color to the header using any of the bg-* classes -->
      <div class="widget-user-header bg-aqua-active">
        <h3 class="widget-user-username">{{$customer_info->mlm_username}}</h3>
        <h4 class="widget-user-desc">{{name_format_from_customer_info($customer_info)}}</h4>
        <h5 class="widget-user-desc">{{$slot_now->membership_name}}</h5>
        <!-- <h5 class="widget-user-desc">@if(isset($slot_now->slot_no)) {{$slot_now->slot_no}} @else No Slot @endif</h5> -->
      </div>
      <div class="widget-user-image">
        <img class="img-circle" src="{{$profile}}" alt="User Avatar">

      </div>
      <div class="box-footer">
        <div class="row">
          <div class="col-sm-4 border-right">
            <div class="description-block">
              <h5 class="description-header">{{$count_direct}}</h5>
              <span class="description-text">Direct Referral</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-4 border-right">
            <div class="description-block">
              <h5 class="description-header">@if(isset($slot_now->slot_no)) {{$slot_now->slot_no}} @else No Slot @endif</h5>
              <span class="description-text">Slot</span>
            </div>
            <!-- /.description-block -->
          </div>
          <div class="col-sm-4 border-right">
            <div class="description-block">
              <h5 class="description-header">{{currency('PHP', $current_wallet)}}</h5>
              <span class="description-text">Current Wallet</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
    </div>
    <!-- /.widget-user -->
  </div>


<div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Income Summary</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table table-bordered">
          <tbody>
            @if(isset($slot_stairstep))
            <tr>
              <td>Stairstep Rank</td>
              <td>{{$slot_stairstep}}</td>
            </tr>
            @endif
            @if(isset($slot_now))
              @if($slot_now != null)
                  @if (Session::has('message'))
                    <tr>
                      <td colspan="20" class="badge bg-red">{{ Session::get('message') }}</td>
                    </tr>
                  @endif
                  @foreach($plan_settings as $key => $value)
                    <tr>
                      <td>{{$value->marketing_plan_label}}</td>
                      <td>{{currency('PHP', $earning[$key])}}</td>
                    </tr>
                  @endforeach
                  @if($single_leg_income != 0)
                    <tr>
                      <td>Single Line Binary Income</td>
                      <td>{{currency('PHP', $single_leg_income)}}</td>
                    </tr>
                  @endif
                  @if(isset($slot_stairstep)) 
                    <tr>
                      <td>Rebates Bonus</td>
                      <td>{{currency('PHP', $rebates)}}</td>
                    </tr>
                    <tr>
                      <td>Stairstep Bonus</td>
                      <td>{{currency('PHP', $override)}}</td>
                    </tr>
                    <tr>
                      <td>Total</td>
                      <td>{{currency('PHP', array_sum($earning) + $rebates + $override + $single_leg_income)}}</td>
                    </tr>
                  @else
                    <tr>
                      <td>Total</td>
                      <td>{{currency('PHP', array_sum($earning) + $single_leg_income)}}</td>
                    </tr>
                  @endif
              @else
                <tr>
                  <td colspan="20">No Active Income yet.</td>
                </tr>
              @endif
          @else
            <tr>
              <td colspan="20">No Active Income yet.</td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>

  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Points Summarry</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table table-bordered">
          <tbody>
          @if(isset($slot_now))
              @if($slot_now != null)
                  @if (Session::has('message'))
                    <tr>
                      <td colspan="2" class="badge bg-red">{{ Session::get('message') }}</td>
                    </tr>
                  @endif

                  <!-- @foreach($plan_settings_2 as $key => $value)
                    <tr>
                      <td>{{$value->marketing_plan_label}}</td>
                      <td>{{$earning_2[$key]}}</td>
                    </tr>
                  @endforeach 
                    <?php if(!isset($earning_2)) $earning_2[0] = 0; ?>
                    <tr>
                      <td>Total</td>
                      <td>{{array_sum($earning_2)}}</td>
                    </tr> -->

                  
                    <tr>
                      <td>Rank Points</td>
                      <td>{{$rank_points}}</td>
                    </tr>
                    <tr>
                      <td>Personal Maintenance Points</td>
                      <td>{{$personal_maintenance_points}}</td>
                    </tr>
                  

                  @if($binary == 1)
                    <tr>
                      <td>Binary Left Points</td>
                      <td>{{$left}}</td>
                    </tr>
                    <tr>
                      <td>Binary Right Points</td>
                      <td>{{$right}}</td>
                    </tr>
                  @endif
              @else
                  <tr>
                    <td colspan="20">No Active Income yet.</td>
                  </tr>
              @endif
          @else
            <tr>
              <td colspan="20">No Active Income yet.</td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>