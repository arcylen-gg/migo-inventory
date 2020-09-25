@extends('mlm.layout')
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
         <div class="row x_title">
            <div class="col-md-6">
               <h3>Income Summary <small>Pairing and Direct Referral</small></h3>
            </div>
            <div class="col-md-6">
               <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  <span>April 1, 2017 - May 1, 2015</span> <b class="caret"></b>
               </div>
            </div>
         </div>
         <div class="col-md-9 col-sm-9 col-xs-12">
            <div id="chart_plot_01" class="demo-placeholder"></div>
         </div>
         <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
            <div class="x_title">
               <h2>Performance Summary</h2>
               <div class="clearfix"></div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-6">
               <div>
                  <p>Direct Referral - {{currency('PHP', $direct)}}</p>
                  <div class="">
                     <div class="progress progress_sm" style="width: 100%;">
                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{$direct_percent}}"></div>
                     </div>
                  </div>
               </div>
               <div>
                  <p>Pairing - {{currency('PHP', $binary)}}</p>
                  <div class="">
                     <div class="progress progress_sm" style="width: 100%;">
                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{$binary_percent}}"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-4 col-sm-4 col-xs-12">
      <div class="x_panel">
         <div class="x_title">
            <h2>Recent Activities <small>Sessions</small></h2>
            <ul class="nav navbar-right panel_toolbox">
               <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
               </li>
               <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                  <ul class="dropdown-menu" role="menu">
                     <li><a href="#">Settings 1</a>
                     </li>
                     <li><a href="#">Settings 2</a>
                     </li>
                  </ul>
               </li>
               <li><a class="close-link"><i class="fa fa-close"></i></a>
               </li>
            </ul>
            <div class="clearfix"></div>
         </div>
         <div class="x_content">
            <div class="dashboard-widget-content">
               <ul class="list-unstyled timeline widget">
                  <li>
                     <div class="block">
                        <div class="block_content">
                           @foreach($recent_activity as $key => $value)
                           <h2 class="title">
                              <a>{{$value->wallet_log_details}}</a>
                           </h2>
                           <div class="byline">
                              <span>{{$value->ago}}</span>
                           </div>
                           <p class="excerpt"> Wallet Amount: {{$value->wallet_log_amount}}<a class="hide">Details</a>
                           </p>
                           @endforeach
                        </div>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-8 col-sm-8 col-xs-12">
      <div class="row">
         <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
               <div class="x_title">
                  <h2>Member's location <small>geo-presentation</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                     <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                     </li>
                     <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                           <li><a href="#">Settings 1</a>
                           </li>
                           <li><a href="#">Settings 2</a>
                           </li>
                        </ul>
                     </li>
                     <li><a class="close-link"><i class="fa fa-close"></i></a>
                     </li>
                  </ul>
                  <div class="clearfix"></div>
               </div>
               <div class="x_content">
                  <div class="dashboard-widget-content">
                     <div class="col-md-4 hidden-small">
                        <h2 class="line_30">{{$count_downline}} members/downlines from different countries</h2>
                        <table class="countries_list">
                           <tbody>
                              @foreach($country_name as $key => $value)
                              <tr>
                                 <td>{{$key}}</td>
                                 <td class="fs15 fw700 text-right">{{number_format($value, 2)}}%</td>
                              </tr>
                              @endforeach
                              <tr class="hide">
                                 <td>Hongkong</td>
                                 <td class="fs15 fw700 text-right">27%</td>
                              </tr>
                              <tr class="hide">
                                 <td>China</td>
                                 <td class="fs15 fw700 text-right">16%</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <div id="world-map-gdp" class="col-md-8 col-sm-12 col-xs-12" style="height:230px;"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('js')

@endsection
@section('css')

@endsection