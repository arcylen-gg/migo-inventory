<div class="col-md-12">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="fa fa-sitemap"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">ACTIVE SLOTS:</span>
          <span class="info-box-number">{{$count_all_slot_active}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="fa fa-sitemap"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">INACTIVE SLOTS:</span>
          <span class="info-box-number">{{$count_all_slot_inactive}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
</div>
<div class="col-md-12">
    <?php $total_membership_price = 0; ?>
    @foreach($membership as $key => $value)
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="fa fa-flag-checkered"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{$value->membership_name}}: </span>
          <span class="info-box-number">
            {{$membership_count[$key]}}
            <?php $total_membership_price += $membership_price[$key];?>
           </span>
        </div>
      </div>
    </div> 
    @endforeach
</div>
<div class="col-md-12">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="fa fa-user"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Registered Accounts: </span>
          <span class="info-box-number">
            {{$customer_account}}
           </span>
        </div>
      </div>
    </div> 
</div>

<div class="container">
    <div class="row">
    </div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="col-md-6">
        <canvas  id="myChart" width="100%" height="50%"></canvas>
    </div>
    <div class="col-md-6">
        <?php $total_wallet = 0; ?>
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Pending : </span>
              <span class="info-box-number">
                {{number_format($not_encashed)}}
               </span>
            </div>
          </div>
        </div> 
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Requested : </span>
              <span class="info-box-number">
                {{number_format($not_encashed_requested * (-1))}}
               </span>
            </div>
          </div>
        </div> 
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Released : </span>
              <span class="info-box-number">
                {{number_format($not_encashed_encashed * (-1))}}
               </span>
            </div>
          </div>
        </div> 
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total : </span>
              <span class="info-box-number">
                {{number_format($total_wallet += ($not_encashed_encashed * (-1))+ ($not_encashed_requested * (-1)) + $not_encashed )}}
               </span>
            </div>
          </div>
        </div> 
    </div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="col-md-6">
        <canvas  id="myChart_encashment" width="100%" height="50%"></canvas>
    </div>
    <div class="col-md-6">
        <?php $total_income_per_complan = 0; ?>
        @foreach($chart_per_complan_raw['plan'] as $key => $value)
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">{{$value}} : </span>
              <span class="info-box-number">
                 {{number_format($chart_per_complan_raw['series'][$key])}}
               </span>
            </div>
          </div>
        </div> 
        <?php $total_income_per_complan += $chart_per_complan_raw['series'][$key]; ?>
        @endforeach
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total : </span>
              <span class="info-box-number">
                {{number_format($total_income_per_complan)}}
               </span>
            </div>
          </div>
        </div> 
    </div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="col-md-6">
        <canvas  id="myChart_in_out" width="100%" height="50%"></canvas>
    </div>
    <div class="col-md-6">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">PAYIN : </span>
              <span class="info-box-number">
                {{number_format($total_membership_price)}}
               </span>
            </div>
          </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">EXPECTED PAYOUT: </span>
              <span class="info-box-number">
                {{number_format($total_income_per_complan)}}
               </span>
            </div>
          </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text"> COMPANY EARNINGS : </span>
              <span class="info-box-number">
                <?php $div = 0;  if($total_income_per_complan == 0){$div = 0;} else{ $div = $total_income_per_complan/$total_membership_price; }?>
               {{$total_membership_price - $total_income_per_complan }} ({{  ((($div) - 1) * (-1)) *100}}%)
               </span>
            </div>
          </div>
        </div> 
    </div>
</div>





<script>
var ctx = document.getElementById("myChart");
var ctx2 = document.getElementById("myChart_encashment");
var ctx3 = document.getElementById("myChart_in_out");
var datass = {!! $chart_per_complan !!};
var datass_encashment = {!! $encashment_json !!};
var backgrounds = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ];
var border =  [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ];    
var options =    {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    };                
// console.log(datass);
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: datass_encashment.labels,
        datasets: [{
            label: 'WALLET ENCASHMENT',
            data: datass_encashment.data,
            backgroundColor: backgrounds,
            borderColor: border,
            borderWidth: 1
        }]
    },
    options: options
});


var myChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: datass.labels,
        datasets: [{
            label: 'INCOME PER COMPLAN',
            data: datass.data,
            backgroundColor: backgrounds,
            borderColor: border,
            borderWidth: 1
        }]
    },
    options: options
});

var myChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ["PAYIN", "EXPECTED PAYOUT"],
        datasets: [{
            label: 'PAYIN/PAYOUT',
            data: [{{$total_membership_price}}, {{$total_income_per_complan}}],
            backgroundColor: backgrounds,
            borderColor: border,
            borderWidth: 1
        }]
    },
    options: options
});


</script>