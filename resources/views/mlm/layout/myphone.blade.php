<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Brown and Proud | Members Control Panel</title>
      <!-- Bootstrap -->
      <link href="/assets/member-theme/myphone/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="/assets/dist/css/AdminLTE.min.css">
      <!-- Font Awesome -->
      <link href="/assets/member-theme/myphone/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" href="/resources/assets/distributor/styles/6227bbe5.font-awesome.css" type="text/css" />
      <!-- NProgress -->
      <link href="/assets/member-theme/myphone/vendors/nprogress/nprogress.css" rel="stylesheet">
      <!-- iCheck -->
      <link href="/assets/member-theme/myphone/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
      <!-- bootstrap-progressbar -->
      <link href="/assets/member-theme/myphone/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
      <!-- JQVMap -->
      <link href="/assets/member-theme/myphone/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
      <!-- bootstrap-daterangepicker -->
      <link href="/assets/member-theme/myphone/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
      <!-- Custom Theme Style -->
      <link href="/assets/member-theme/myphone/build/css/custom.min.css" rel="stylesheet">

      <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css">
      <link rel="stylesheet" href="/assets/member/css/member.css" type="text/css"/>
      <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">
      <style>
         .navbar, .left_col { background-color: #5c3424; }
         #menu_toggle { color: #5c3424; }
         body { background-color: #5c3424! important; }
         .nav.side-menu>li.active>a  { background: none; }
         .nav.side-menu>li.active, .nav.side-menu>li.current-page { border-right: 5px solid orange; }
         .sidebar-footer { background: orange; }

         div.box
         {
          height: auto;
          border: 0;
          background-color: #fff;
          position: relative;
          border-radius: 3px;
          background: #ffffff;
          margin-bottom: 20px;
          width: 100%;
          box-shadow: 0 1px 1px rgba(0,0,0,0.1);
         }

        .box-header.with-border 
        {
          border-bottom: 1px solid #f4f4f4;
        }

        .box-header 
        {
          color: #444;
          display: block;
          padding: 10px;
          position: relative;
        }

        .box-header .fa
        {
          display: inline-block;
          font-size: 18px;
          margin: 0;
          line-height: 1;
          margin-right: 5px;
        }

        .box-header .box-title
        {
          display: inline-block;
          font-size: 18px;
          margin: 0;
          line-height: 1;
        }

        .box-body 
        {
          border-top-left-radius: 0;
          border-top-right-radius: 0;
          border-bottom-right-radius: 3px;
          border-bottom-left-radius: 3px;
          padding: 10px;
        }

        .nav.side-menu>li>ul>li>a
        {
          color: #fff;
          display: block;
          padding: 7.5px 0;
        }

        .box.box-primary
        {
          overflow: hidden;
        }

        .bg-aqua, .callout.callout-info, .alert-info, .label-info, .modal-info .modal-body
        {
          background-color: #5C3424 !important;
        }

        .timeline::before
        {
          left: 3.5px;
        }

        .content 
        {
            min-height: 250px;
            padding: 0px;
            margin-right: auto;
            margin-left: auto;
        }

        .info-box
        {
          border: 1px solid #D9DEE4;
        }

        .info-box-icon
        {
          height: 88px;
        }

        .info-box-text h2
        {
          font-weight: 700;
          margin-bottom: 5px;
        }

        .info-box-content
        {
          margin-top: 5px;
        }
      </style>
      @yield("css")
   </head>
   <body class="nav-md">
      <div class="container body">
         <div class="main_container">
            <div class="col-md-3 left_col">
               <div class="left_col scroll-view">
                  <div class="navbar nav_title" style="border: 0;">
                     <a href="index.html" class="site_title"><img src="/assets/member-theme/myphone/images/Brown_Logo.png" alt="Brown and Proud"></a>
                  </div>
                  <div class="clearfix"></div>
                  <!-- menu profile quick info -->
                  <div class="profile clearfix">
                     <div class="profile_pic">
                        <img src="{{$profile}}" alt="..." class="img-circle profile_img" height="65spx;">
                     </div>
                     <div class="profile_info">
                        <span>Magandang Araw (Good Day) ,</span>
                        <h2>{{$customer_info->title_name}} {{$customer_info->first_name}} {{$customer_info->last_name}} {{$customer_info->suffix_name}}</h2>
                     </div>
                  </div>
                  <!-- /menu profile quick info -->
                  <br />
                  <!-- sidebar menu -->
                  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                     <div class="menu_section">
                        <h3>Members Dashboard</h3>
                        <ul class="nav side-menu">
                           <li><a href="/mlm"> Home <span class="fa fa-chevron-down"></span></a></li>
                           <li><a href="/mlm/profile"> Profile <span class="fa fa-chevron-down"></span></a></li>
                           <li><a href="/mlm/notification"> Notification <span class="fa fa-chevron-down"></span></a></li>
                           <li><a href="/mlm/repurchase"> Repurchase <span class="fa fa-chevron-down"></span></a></li>
                           <li>
                            <a> Genealogy <span class="fa fa-chevron-down"></span></a>
                            <ul style="display: none;">
                              <li>
                                  <a href="/mlm/genealogy/binary">
                                      Binary Genealogy
                                  </a> 
                              </li>
                              <li>
                                <a href="/mlm/network/binary">
                                  Binary Network List
                                </a>
                              </li>
                              @if(count($complan_repurchase) >=1)
                                  @foreach($complan_repurchase as $value)
                                  @if($value->marketing_plan_code == 'TRIANGLE_REPURCHASE')
                                    <li>
                                        <a href="/mlm/genealogy/repurchase" class="subnav-text">
                                            Repurchase Genealogy
                                        </a> 
                                    </li>
                                  @endif
                                  @endforeach
                              @endif
                            </ul>
                           </li>
                           <li><a> Report <span class="fa fa-chevron-down"></span></a>
                            <ul style="display: none;">
                              @if(count($complan) >=1)
                                  @foreach($complan as $value)
                                  <li>
                                      <a href="/mlm/report/{{strtolower($value->marketing_plan_code) }}" class="subnav-text">
                                          {{$value->marketing_plan_label}}
                                      </a> 
                                  </li>
                                  @endforeach
                              @endif
                              @if(count($complan_repurchase) >=1)
                                  @foreach($complan_repurchase as $value)
                                  <li>
                                      <a href="/mlm/report/{{strtolower($value->marketing_plan_code) }}" class="subnav-text">
                                          {{$value->marketing_plan_label}}
                                      </a> 
                                  </li>
                                  @endforeach
                              @endif
                            </ul>
                           </li>
                           <li class="hide"><a> Vouchers <span class="fa fa-chevron-down"></span></a></li>
                           <li>
                            <a> Wallet <span class="fa fa-chevron-down"></span></a>
                            <ul style="display: none;">
                              <li>
                                <a href="/mlm/wallet" class="subnav-text">
                                    Wallet Logs
                                </a> 
                                <a href="/mlm/refill" class="subnav-text">
                                    Wallet Refill
                                </a> 
                                <a href="/mlm/transfer" class="subnav-text">
                                    Wallet Transfer
                                </a> 
                                <a href="/mlm/encashment" class="subnav-text">
                                    Wallet Encashment
                                </a> 
                            </li>
                            </ul>
                            </li>
                              <li><a href="/mlm/slots"> My Slots <span class="fa fa-chevron-down"></span></a></li>
                              <li><a href="/mlm/lead"> Lead <span class="fa fa-address-card"></span></a></li>
                        </ul>
                     </div>
                  </div>
                  <!-- /sidebar menu -->
                  <!-- /menu footer buttons -->
                  <div class="sidebar-footer hidden-small">
                     <a data-toggle="tooltip" data-placement="top" title="Settings">
                     <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                     </a>
                     <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                     <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                     </a>
                     <a data-toggle="tooltip" data-placement="top" title="Lock">
                     <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                     </a>
                     <a data-toggle="tooltip" data-placement="top" title="Logout" href="/mlm/login">
                     <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                     </a>
                  </div>
                  <!-- /menu footer buttons -->
               </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
               <div class="nav_menu">
                  <nav>
                     <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                     </div>
                     <ul class="nav navbar-nav navbar-right">
                        <li class="">
                           <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                           <img src="{{$profile}}" alt="">{{$customer_info->title_name}} {{$customer_info->first_name}} {{$customer_info->last_name}} {{$customer_info->suffix_name}}
                           <span class=" fa fa-angle-down"></span>
                           </a>
                           <ul class="dropdown-menu dropdown-usermenu pull-right">
                              <li><a href="javascript:;"> Profile</a></li>
                              <li>
                                 <a href="javascript:;">
                                 <span class="badge bg-red pull-right">50%</span>
                                 <span>Settings</span>
                                 </a>
                              </li>
                              <li><a href="javascript:;">Help</a></li>
                              <li><a href="/mlm/login"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                           </ul>
                        </li>
                        <li role="presentation" class="dropdown">
                           <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                           <i class="fa fa-envelope-o"></i>
                           <span class="badge bg-green">{{$notification_count}}</span>
                           </a>
                           <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                              <li class="hide">
                                 <a>
                                 <span class="image"><img src="/assets/member-theme/myphone/images/img.jpg" alt="Profile Image" /></span>
                                 <span>
                                 <span>John Smith</span>
                                 <span class="time">3 mins ago</span>
                                 </span>
                                 <span class="message">
                                 Film festivals used to be do-or-die moments for movie makers. They were where...
                                 </span>
                                 </a>
                              </li>
                              @if(isset($notification))
                                  @foreach($notification as $key => $value)
                                  <li>
                                    <a href="/mlm/notification">
                                      <span class="image"><i class="fa fa-users text-aqua"></i></span>
                                      <span>
                                        <span>{{$value->slot_no}}</span>
                                        <span class="time">{{$value->wallet_log_date_created}}</span>
                                       </span>
                                       <span class="message">
                                       {{$value->wallet_log_details}}
                                       </span>
                                    </a>
                                  </li>
                                  @endforeach
                              @endif
                              <li>
                                 <div class="text-center">
                                    <a href="/mlm/notification">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                    </a>
                                 </div>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </nav>
               </div>
            </div>
            <!-- /top navigation -->
            <!-- page content -->
            <div class="right_col" role="main">
              <div class="content">
                @yield("content")
              </div>
            </div>
            <!-- /page content -->
            <!-- footer content -->
            <footer>
               <div class="pull-right">
                  Brown Members Panel
               </div>
               <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
         </div>
      </div>
      <div id="global_modal" class="modal fade" role="dialog" >
          <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                  
              </div>
          </div>
      </div>

      <div class="modal-loader hidden"></div>
        <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modal Header</h4>
                        </div>
                        <div class="modal-body">
                            <p>Some text in the modal.</p>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
      </div>
      <!-- jQuery -->
      <script src="/assets/member-theme/myphone/vendors/jquery/dist/jquery.min.js"></script>
      <!-- Bootstrap -->
      <script src="/assets/member-theme/myphone/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
      <!-- FastClick -->
      <script src="/assets/member-theme/myphone/vendors/fastclick/lib/fastclick.js"></script>
      <!-- NProgress -->
      <script src="/assets/member-theme/myphone/vendors/nprogress/nprogress.js"></script>
      <!-- Chart.js -->
      <script src="/assets/member-theme/myphone/vendors/Chart.js/dist/Chart.min.js"></script>
      <!-- gauge.js -->
      <script src="/assets/member-theme/myphone/vendors/gauge.js/dist/gauge.min.js"></script>
      <!-- bootstrap-progressbar -->
      <script src="/assets/member-theme/myphone/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
      <!-- iCheck -->
      <script src="/assets/member-theme/myphone/vendors/iCheck/icheck.min.js"></script>
      <!-- Skycons -->
      <script src="/assets/member-theme/myphone/vendors/skycons/skycons.js"></script>
      <!-- Flot -->
      <script src="/assets/member-theme/myphone/vendors/Flot/jquery.flot.js"></script>
      <script src="/assets/member-theme/myphone/vendors/Flot/jquery.flot.pie.js"></script>
      <script src="/assets/member-theme/myphone/vendors/Flot/jquery.flot.time.js"></script>
      <script src="/assets/member-theme/myphone/vendors/Flot/jquery.flot.stack.js"></script>
      <script src="/assets/member-theme/myphone/vendors/Flot/jquery.flot.resize.js"></script>
      <!-- Flot plugins -->
      <script src="/assets/member-theme/myphone/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
      <script src="/assets/member-theme/myphone/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
      <script src="/assets/member-theme/myphone/vendors/flot.curvedlines/curvedLines.js"></script>
      <!-- DateJS -->
      <script src="/assets/member-theme/myphone/vendors/DateJS/build/date.js"></script>
      <!-- JQVMap -->
      <script src="/assets/member-theme/myphone/vendors/jqvmap/dist/jquery.vmap.js"></script>
      <script src="/assets/member-theme/myphone/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
      <script src="/assets/member-theme/myphone/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
      <!-- bootstrap-daterangepicker -->
      <script src="/assets/member-theme/myphone/vendors/moment/min/moment.min.js"></script>
      <script src="/assets/member-theme/myphone/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
      <!-- Custom Theme Scripts -->
      <script src="/assets/member-theme/myphone/build/js/custom.min.js"></script>

      <!-- From Default Template -->
      
      <!-- AdminLTE App -->
      <script src="/assets/dist/js/app.min.js"></script>

      <!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
      <!-- // <script src="/resources/assets/distributor/scripts/e1d08589.bootstrap.min.js"></script> -->
      <script src="/resources/assets/distributor/scripts/vendor/modernizr.js"></script>
      <script src="/resources/assets/distributor/scripts/9f7a46ed.proton.js"></script>


      <script src="/resources/assets/distributor/scripts/vendor/jquery.jstree.js"></script>

      <script src="/resources/assets/distributor/scripts/vendor/raphael-min.js"></script>
      <script src="/resources/assets/distributor/scripts/vendor/morris.min.js"></script>

      <script src="/resources/assets/distributor/scripts/vendor/jquery.textareaCounter.js"></script>

      <script src="/resources/assets/distributor/scripts/vendor/fileinput.js"></script>       

      <!-- View Voucher -->
      <script type="text/javascript" src="/resources/assets/distributor/js/voucher.js"></script>
      <script type="text/javascript" src="/resources/assets/remodal/src/jquery.remodal.js"></script>
      <div id="luke"></div>

      <!-- ADDITIONALS -->

      <script type="text/javascript" src="/assets/mlm/js/slot_change.js"></script>
      <script type="text/javascript" src="/assets/member/global.js"></script>
      <script type="text/javascript" src="/assets/external/chosen/chosen/chosen.jquery.js"></script> 
      <script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
      <script type="text/javascript" src="/assets/external/chosen/chosen/chosen.jquery.js"></script>
      <script type="text/javascript" src="/assets/mlm/pace.min.js"></script>
      <script type="text/javascript">
      $(document).ajaxStart(function() { Pace.restart(); });

      @if (Session::has('success'))
      toastr.success("{{ Session::get('success') }}");
      @endif  
      @if (Session::has('warning'))
      toastr.warning("{{ Session::get('warning') }}");
      @endif  
      </script>

      @yield('js')   
   </body>
</html>