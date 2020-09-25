<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <?php
      header("cache-Control: no-store, no-cache, must-revalidate");
      header("cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
      header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
  ?>
  <base href="<?php echo "http://" . $_SERVER["SERVER_NAME"] ?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Company Member</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/dist/font-awesome-4.7.0/css/font-awesome.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/dist/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="/dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="/resources/assets/distributor/styles/6227bbe5.font-awesome.css" type="text/css" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- <link rel="stylesheet" href="/resources/assets/distributor/styles/aaf5c053.proton.css"> -->
  <!-- Page-specific Plugin CSS: -->
  <link rel="stylesheet" href="/resources/assets/distributor/styles/vendor/select2/select2.css">
  <link rel="stylesheet" href="/resources/assets/distributor/styles/vendor/uniformjs/css/uniform.default.css">

  <!-- Remodal -->
  <link rel="stylesheet" type="text/css" href="/resources/assets/remodal/src/remodal-default-theme.css">
  <link rel="stylesheet" type="text/css" href="/resources/assets/remodal/src/jquery.remodal.css">
  <link rel="stylesheet" type="text/css" href="/resources/assets/distributor/css/voucher.css">

  <link rel="stylesheet" href="/resources/assets/distributor/styles/vendor/animate.css">
  <link href="/assets/front/img/new_front/img/logo.png" rel="icon" sizes="192x192">

  <link rel="stylesheet" href="/assets/member/css/member.css" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css">
  <link rel="stylesheet" href="/assets/external/chosen/chosen/chosen.css" media="screen"/>
  <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">



  @yield('css')
  <script type="text/javascript" src="/resources/assets/external/jquery.min.js"></script>
  <script src="/resources/assets/distributor/scripts/vendor/modernizr.js"></script>
  <script src="/resources/assets/distributor/scripts/vendor/jquery.cookie.js"></script>
  <link rel="stylesheet" type="text/css" href="/assets/mlm/pace.css">
  <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">
    <script>
    (function () {
    var js;
    if (typeof JSON !== 'undefined' && 'querySelector' in document && 'addEventListener' in window) {
    js = '/assets/external/jquery.minv2.js';
    } else {
    js = '/assets/external/jquery.minv1.js';
    }
    document.write('<script src="' + js + '"><\/script>');
    }());
    </script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <script>
  //     var theme = $.cookie('protonTheme') || 'default';
  //     $('body').removeClass (function (index, css) {
  //             return (css.match (/\btheme-\S+/g) || []).join(' ');
  //     });
  //     if (theme !== 'default') $('body').addClass(theme);
  </script>
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="/mlm" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>@if(isset($content['company_acronym'])) {{$content['company_acronym']}} @endif</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" style="text-transform: uppercase;"><b>{{$shop_info->shop_key}}</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu hide">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the messages -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <!-- User Image -->
                        <img src="{{$profile}}" class="img-circle" alt="User Image">
                      </div>
                      <!-- Message title and timestamp -->
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <!-- The message -->
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- /.messages-menu -->

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">@if(isset($notification_count)) @if($notification_count >= 1) ({{$notification_count}}) @else 0 @endif @else 0 @endif</span>
            </a>
            <ul class="dropdown-menu" style="width: 350px !important">
              <li class="header">You have @if(isset($notification_count)) @if($notification_count >= 1) ({{$notification_count}}) @else 0 @endif @else 0 @endif notifications</li>
              <li>
                <!-- Inner Menu: contains the notifications -->
                <ul class="menu">
                @if(isset($notification))
                    @foreach($notification as $key => $value)
                    <li><!-- start notification -->
                      <a href="/mlm/notification">
                        <!-- <i class="fa fa-users text-aqua"></i> {{$value->wallet_log_details}} -->
                        <div class="box-comment">
                          <!-- User image -->
                          <img class="img-circle img-sm" src="{{mlm_profile_link($value)}}" alt="User Image">

                          <div class="comment-text">
                                <span class="username">
                                  {{$value->slot_no}}
                                  <span class="text-muted pull-right">{{$value->wallet_log_date_created}}</span>
                                  <br>
                                  <?php 
                                    $in = $value->wallet_log_details;
                                    $out = strlen($in) > 40 ? substr($in,0,40)."..." : $in;
                                    echo $out;
                                  ?>
                                </span><!-- /.username -->
                                <br>
                                <span class="text-muted"><p></p></span>
                            
                          </div>
                          <!-- /.comment-text -->
                        </div>
                      </a>
                    </li>
                    @endforeach
                @else
                <li><!-- start notification -->
                  <a href="/mlm/notification">
                    <i class="fa fa-users text-aqua"></i> No Active Notification
                  </a>
                </li>
                @endif
                  <!-- end notification -->
                </ul>
              </li>
              <li class="footer"><a href="/mlm/notification">View all</a></li>
            </ul>
          </li>
          <!-- Tasks Menu -->
          <li class="dropdown tasks-menu hide">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- Inner menu: contains the tasks -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <!-- Task title and progress text -->
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <!-- The progress bar -->
                      <div class="progress xs">
                        <!-- Change the css width attribute to simulate progress -->
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{$profile}}" width="25px" height="25px" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{$customer_info->title_name}} {{$customer_info->first_name}} {{$customer_info->middle_name}} {{$customer_info->last_name}} {{$customer_info->suffix_name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{$profile}}" width="50px" height="50px"  class="img-circle" alt="User Image">

                <p>
                  {{$customer_info->title_name}} {{$customer_info->first_name}} {{$customer_info->middle_name}} {{$customer_info->last_name}} {{$customer_info->suffix_name}}
                  <small>Member since {{$customer_info->created_date}}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body hide" style="background-color: #b8c7ce;">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer" style="background-color: #b8c7ce;">
                <div class="pull-left">
                  <a href="/mlm/profile" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="/mlm/login" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{$profile}}" width="160px" height="160px" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{name_format_from_customer_info($customer_info)}}</p>
          <!-- Status -->
          <a href="javascript:"><i class="fa fa-circle text-success"></i> @if(isset($slot_now->slot_no)) {{$slot_now->slot_no}} @else No Active Slot @endif</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <!-- /.sidebar-menu -->
      <ul class="sidebar-menu">
                <li class="{{Request::segment(2) == null ? 'active' : '' }}" >
                    <a href="/mlm">
                        <i class="icon-home nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="{{Request::segment(2) == 'profile' ? 'active' : '' }}">
                    <a href="/mlm/profile">
                        <i class="icon-user nav-icon"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                </li>
                <?php 
                $access_repurchase['alphaglobal'] = '/mlm/repurchase';
                $link_repurchase = '/mlm/repurchase';
                if(!isset($access_repurchase[$shop_info->shop_key]))
                {
                  $link_repurchase = '/';
                }
                ?>
                <li class="{{Request::segment(2) == 'repurchase' ? 'active' : '' }}">
                    <a href="{{$link_repurchase}}">
                        <i class="icon-shopping-cart nav-icon"></i>
                        <span class="nav-text">Repurchase</span>
                    </a>
                </li>
                @if($discount_card_log != null)
                <li class="treeview" class="{{Request::segment(3) == 'report' ? 'active' : '' }}">
                  <a href="javascript:">
                        <i class="icon-list nav-icon"></i>
                        <span class="nav-text">Report</span>
                        <i class="icon-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li>
                          <a href="/mlm/discount_card/report" class="subnav-text">
                              Repurchase Report
                          </a> 
                      </li>
                    </ul>
                </li>
                <li class="{{Request::segment(3) == 'upgrade' ? 'active' : '' }}">
                    <a href="/mlm/discount_card/upgrade">
                        <i class="icon-collapse-top"></i>
                        <span class="nav-text">Membership Upgrade</span>
                    </a>
                </li>
                @else
                <li class="{{Request::segment(2) == 'notification' ? 'active' : '' }}">
                    <a href="/mlm/notification">
                        <i class="icon-star nav-icon"></i>
                        <span class="nav-text">Notification @if(isset($notification_count)) @if($notification_count >= 1) ({{$notification_count}}) @endif @endif</span>
                    </a>
                </li>
                
                <li class="treeview" class="{{Request::segment(2) == 'genealogy' ? 'active' : '' }}">
                    <a href="javascript:">
                        <i class="icon-sitemap nav-icon"></i>
                        <span class="nav-text">Genealogy</span>
                        <i class="icon-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="">
                            <a  href="/mlm/genealogy/unilevel" class="subnav-text">
                                Unilevel Genealogy
                            </a> 
                        </li >
                        <li class="">
                          <a href="/mlm/network/unilevel" class="subnav-text">
                            Unilevel Network List
                          </a>
                        </li>
                        <?php $enable_binary = 0; 
                        foreach($complan as $key => $value)
                        {
                            if($value->marketing_plan_code == 'BINARY')
                            {
                                $enable_binary = 1;
                            }
                        }
                        ?>
                        @if($enable_binary == 1)
                            <li>
                                <a  href="/mlm/genealogy/binary" class="subnav-text">
                                    Binary Genealogy
                                </a> 
                            </li>
                            <li>
                              <a href="/mlm/network/binary" class="subnav-text">
                                Binary Network List
                              </a>
                          </li>
                        @endif
                    </ul>
                </li>
                <!--<li>-->
                <!--    <a href="/mlm/product">-->
                <!--        <i class="icon-shopping-cart nav-icon"></i>-->
                <!--        <span class="nav-text">Products</span>-->
                <!--    </a>-->
                <!--</li> -->

                <li class="treeview" class="{{Request::segment(2) == 'report' ? 'active' : '' }}">
                    <a href="javascript:">
                        <i class="icon-list nav-icon"></i>
                        <span class="nav-text">Report</span>
                        <i class="icon-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
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

                        <?php
                          $active_school_wallet['PhilTECH'] = 'PhilTECH';
                        ?>
                        @if(isset($active_school_wallet[$shop_info->shop_key]))
                            <li>
                                <a href="/mlm/report/school_wallet" class="subnav-text">
                                    School Wallet
                                </a> 
                            </li>
                        @endif
                        @if(isset($shop_info->shop_key))
                          @if($shop_info->shop_key == "PhilTECH")
                            <li>
                                <a href="/mlm/report/product_code" class="subnav-text">
                                    Product Code
                                </a> 
                            </li>
                          @endif
                        @endif
                    </ul>
                </li> 
                <li  class="{{Request::segment(2) == 'vouchers' ? 'active' : '' }}">
                    <a href="/mlm/vouchers">
                        <i class="icon-barcode nav-icon"></i>
                        <span class="nav-text">Vouchers</span>
                    </a>
                </li> 
                <li  class="{{Request::segment(2) == 'gc' ? 'active' : '' }}">
                    <a href="/mlm/gc">
                        <i class="icon-barcode nav-icon"></i>
                        <span class="nav-text">Gift Certificates</span>
                    </a>
                </li> 
                <li class="{{Request::segment(2) == 'transfer' ? 'active' : '' }}">
                    <a href="javascript:">
                        <i class="icon-money nav-icon"></i>
                        <span class="nav-text">Wallet</span>
                        <i class="icon-angle-right"></i>
                    </a>   
                    <ul class="treeview-menu">
                        <li>
                            <a href="/mlm/wallet" class="subnav-text">
                                Wallet Logs
                            </a> 

                            @if(isset($shop_info)) 
                              @if($shop_info->shop_key == "PhilTECH")
                                <a href="/mlm/refill" class="subnav-text">
                                    Wallet Refill
                                </a> 
                                <a href="/mlm/transfer" class="subnav-text">
                                    Wallet Transfer
                                </a> 
                              @endif
                            @endif

                            <a href="/mlm/encashment" class="subnav-text">
                                Wallet Encashment
                            </a> 
                            @if($shop_info->shop_wallet_tours == 1)
                                <a href="/mlm/wallet/tours" class="subnav-text">
                                    Airline Wallet
                                </a> 
                            @endif
                            @if($shop_info->shop_wallet_vmoney == 1)
                                <a href="/mlm/wallet/vmoney" class="subnav-text">
                                    E-Money Wallet
                                </a> 
                            @endif
                        </li>
                    </ul> 
                </li>
                <li  class="{{Request::segment(2) == 'slots' ? 'active' : '' }}">
                    <a href="/mlm/slots">
                        <i class="fa fa-linode"></i>
                        <span class="nav-text">My Slots</span>
                    </a>
                </li>
                <li  class="{{Request::segment(2) == 'lead' ? 'active' : '' }}">
                    <a href="/mlm/lead">
                        <i class="fa fa-address-card"></i>
                        <span class="nav-text">Leads</span>
                    </a>
                </li> 
                @endif                   
            </ul>
            <ul class="logout hide">
                <li>
                    <a href="/mlm/login">
                        <i class="icon-off nav-icon"></i>
                        <span class="nav-text">
                            Logout
                        </span>
                    </a>
                </li>  
            </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header hide">
      <h1>mai
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">

    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2016 <a href="#">@if(isset($shop_info->shop_key)) {{$shop_info->shop_key}} @endif</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Change Slot</h3>
        <div class="col-md-12 btn btn-primary" style="color:white;" disabled>
          @if(isset($slot_now->slot_id))
            <span class="text-info" style="color: white;">SLOT #{{ $slot_now->slot_no }} <br>({{$slot_now->membership_name}})</span>
          @else 
            <span class="text-info" style="color: white;">No Slot</span>
          @endif
        </div>
        
          @if(isset($slot))
              @if($slot)                                                    
                  @foreach($slot as $slots)

                        <div class="col-md-12 btn btn-primary" slotid='{{$slots->slot_id}}' href="javascript:" onclick="change_slot({{$slots->slot_id}})">
                         <a class="forslotchanging" slotid='{{$slots->slot_id}}' href="javascript:" onclick="change_slot({{$slots->slot_id}})" style="color: white;">SLOT #{{$slots->slot_no}} <br>({{$slots->membership_name}}) </a></li> 
                         <form id="slot_change_id_{{$slots->slot_id}}" class="" action="/mlm/changeslot" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="slot_id" value="{{$slots->slot_id}}">
                          </form>  
                        </div>
                    @endforeach
              @endif   
            @endif
            <a class="btn btn-primary col-md-12" href="/mlm/slots">View More Slots.</a>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  <div id="global_modal" class="modal fade" role="dialog" >
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                
            </div>
        </div>
    </div>

    <!-- GLOBAL MULTIPLE MODAL -->
    <div class="multiple_global_modal_container"></div>

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
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
    <script type="text/javascript" src="/assets/member/global.js?version=6.2"></script>

<!-- jQuery 2.2.3 -->
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/app.min.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
    <!-- // <script src="/resources/assets/distributor/scripts/e1d08589.bootstrap.min.js"></script> -->
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
