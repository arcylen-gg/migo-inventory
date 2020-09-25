<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7 lt-ie10"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8 lt-ie10"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
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
        <meta name="description" content="Page Description">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

        <link rel="stylesheet" href="/resources/assets/distributor/styles/92bc1fe4.bootstrap.css">
        <link media="screen" href="/resources/assets/distributor/styles/vendor/jquery.pnotify.default.css" rel="stylesheet">

        <!-- Page-specific Plugin CSS: -->
        <link rel="stylesheet" href="/resources/assets/distributor/styles/vendor/select2/select2.css">
        <link rel="stylesheet" href="/resources/assets/distributor/styles/vendor/uniformjs/css/uniform.default.css">

        <!-- Remodal -->
        <link rel="stylesheet" type="text/css" href="/resources/assets/remodal/src/remodal-default-theme.css">
        <link rel="stylesheet" type="text/css" href="/resources/assets/remodal/src/jquery.remodal.css">

        <!-- Voucher -->
        <link rel="stylesheet" type="text/css" href="/resources/assets/distributor/css/voucher.css">
        
        <!-- Proton CSS: -->
        <link rel="stylesheet" href="/resources/assets/distributor/styles/aaf5c053.proton.css">
        <link rel="stylesheet" href="/resources/assets/distributor/styles/vendor/animate.css">
        <link href="/assets/front/img/new_front/img/logo.png" rel="icon" sizes="192x192">



        
        <link rel="stylesheet" href="/assets/member/css/member.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css">
        <link rel="stylesheet" href="/assets/external/chosen/chosen/chosen.css" media="screen"/>
        <!-- adds CSS media query support to IE8   -->
        <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
        <script src="/resources/assets/distributor/scripts/vendor/respond.min.js"></script>
        <![endif]-->

        <!-- GLOBAL CSS -->
        <link rel="stylesheet" type="text/css" href="/assets/mlm/css/global.css">

        <!-- Fonts CSS: -->
        <link rel="stylesheet" href="/resources/assets/distributor/styles/6227bbe5.font-awesome.css" type="text/css" />
        <link rel="stylesheet" href="/resources/assets/distributor/styles/40ff7bd7.font-titillium.css" type="text/css" />
        
        <script type="text/javascript" src="/resources/assets/external/jquery.min.js"></script>
        <script src="/resources/assets/distributor/scripts/vendor/modernizr.js"></script>
        <script src="/resources/assets/distributor/scripts/vendor/jquery.cookie.js"></script>
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
        <style type="text/css">
        nav.main-menu {
            background-color: #2C71B8;
        }
        nav.main-menu li>a {
            color: #fff;
            font-weight: 500;
            letter-spacing: 1px;
        }
        
</style>
        @yield('css')
    </head>

    <body>

        <script>
            var theme = $.cookie('protonTheme') || 'default';
            $('body').removeClass (function (index, css) {
                    return (css.match (/\btheme-\S+/g) || []).join(' ');
            });
            if (theme !== 'default') $('body').addClass(theme);
        </script>
        <!--[if lt IE 8]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

      <nav class="main-menu" data-step='2' data-intro='This is the extendable Main Navigation Menu.' data-position='right'>
            <ul>
                <li>
                    <a href="/mlm">
                        <i class="icon-home nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/mlm/profile">
                        <i class="icon-user nav-icon"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="/mlm/notification">
                        <i class="icon-star nav-icon"></i>
                        <span class="nav-text">Notification @if(isset($notification_count)) @if($notification_count >= 1) ({{$notification_count}}) @endif @endif</span>
                    </a>
                </li>
                <li>
                    <a href="/mlm/repurchase">
                        <i class="icon-shopping-cart nav-icon"></i>
                        <span class="nav-text">Repurchase</span>
                    </a>
                </li>
                <li class="has-subnav">
                    <a href="javascript:">
                        <i class="icon-sitemap nav-icon"></i>
                        <span class="nav-text">Genealogy</span>
                        <i class="icon-angle-right"></i>
                    </a>
                    <ul>
                        <li>
                            <a  href="/mlm/genealogy/unilevel" class="subnav-text">
                                Unilevel Genealogy
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
                        @endif
                    </ul>
                </li>
                <!--<li>-->
                <!--    <a href="/mlm/product">-->
                <!--        <i class="icon-shopping-cart nav-icon"></i>-->
                <!--        <span class="nav-text">Products</span>-->
                <!--    </a>-->
                <!--</li> -->


                <li>
                    <a href="/mlm/vouchers">
                        <i class="icon-barcode nav-icon"></i>
                        <span class="nav-text">Vouchers</span>
                    </a>
                </li> 
                <li>
                    <a href="/mlm/transfer">
                        <i class="icon-link nav-icon"></i>
                        <span class="nav-text">Transfer Wallet</span>
                </li>
                <li class="has-subnav">
                    <a href="javascript:">
                        <i class="icon-list nav-icon"></i>
                        <span class="nav-text">Report</span>
                        <i class="icon-angle-right"></i>
                    </a>
                    <ul>
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
            </ul>
            <ul class="logout">
                <li>
                    <a href="/mlm/login">
                        <i class="icon-off nav-icon"></i>
                        <span class="nav-text">
                            Logout
                        </span>
                    </a>
                </li>  
            </ul>
        </nav>
        <section class="wrapper user-profile extended scrollable">
            <nav class="user-menu">
                <a href="javascript:;" class="main-menu-access">
                    <i class="icon-proton-logo"></i>
                    <i class="icon-reorder"></i>
                </a>
                <!--
                <section class="user-menu-wrapper">     
                    <a href="javascript:;" data-expand=".notifications-view" class="notifications-access unread"><i class="icon-comment-alt"></i><div class="menu-counter">6</div></a>
                </section>
                -->
            </nav> 
            
            <ol class="breadcrumb breadcrumb-nav">
                <li><a href="javascript:"><i class="icon-pushpin"></i></a></li>
                <li class="active">
                    <a href="javascript:" data-toggle="dropdown" style="color: #000 !important">
                        {{$customer_info->title_name}} {{$customer_info->first_name}} {{$customer_info->middle_name}} {{$customer_info->last_name}} {{$customer_info->suffix_name}} </span></b> |
                        <b>
                            @if(isset($slot_now->slot_id))<span class="text-info">Slot No. {{ $slot_now->slot_no }}</span>
                            @else <span class="text-info">No Slot</span>
                            @endif
                        </b>
                    </a>
                    <ul role="menu" class="dropdown-menu dropdown-menu-arrow">
                    	@if(isset($slot))
	                        @if($slot)                                                    
	                            @foreach($slot as $slots)
	                                   <li><a class="forslotchanging" slotid='{{$slots->slot_id}}' href="javascript:" onclick="change_slot({{$slots->slot_id}})">SLOT #{{$slots->slot_no}} <span></a></li> 
	                                   <form id="slot_change_id_{{$slots->slot_id}}" class="" action="/mlm/changeslot" method="post">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="slot_id" value="{{$slots->slot_id}}">
                                       </form>  
                                @endforeach
	                        @endif   
                        @endif
                    </ul>       
                </li>
            </ol>
            <!-- /view/world/currency -->
            <?php $a = 'http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in ("USDEUR", "USDJPY", "USDBGN", "USDCZK", "USDDKK", "USDGBP", "USDHUF", "USDLTL", "USDLVL", "USDPLN", "USDRON", "USDSEK", "USDCHF", "USDNOK", "USDHRK", "USDRUB", "USDTRY", "USDAUD", "USDBRL", "USDCAD", "USDCNY", "USDHKD", "USDIDR", "USDILS", "USDINR", "USDKRW", "USDMXN", "USDMYR", "USDNZD", "USDPHP", "USDSGD", "USDTHB", "USDZAR", "USDISK")&env=store://datatables.org/alltableswithkeys' ?>
            <div class="col-md-12 echange-rate"></div>
            <!-- <div class="col-md-12"> <button class="btn btn-primary" onClick="getajaxtomodal('/view/world/currency', 'World Currency Profiles')">World Currency Profiles</button><hr /></div> -->
             @yield('content')

            <div id="luke"></div>
            <div class="row">
                <!-- Word Counter -->
                <!-- http://bavotasan.com/2011/simple-textarea-word-counter-jquery-plugin/ -->
                <script src="/resources/assets/distributor/scripts/vendor/jquery.textareaCounter.js"></script>
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
        </section>
        
        <script src="/resources/assets/distributor/scripts/e1d08589.bootstrap.min.js"></script>
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
        @yield('js')                            
    </body>
</html>