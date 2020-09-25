<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7 lt-ie10"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8 lt-ie10"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Digima House</title>
    <meta name="description" content="Page Description">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker-standalone.min.css">
    <link rel="stylesheet" href="/assets/member/styles/92bc1fe4.bootstrap.css">
    <link rel="stylesheet" href="/assets/member/styles/vendor/jquery.pnotify.default.css">
    <link rel="stylesheet" href="/assets/member/styles/vendor/select2/select2.css">
    <link rel="stylesheet" href="/assets/member/styles/vendor/datatables.css" media="screen"/>
    <link rel="stylesheet" href="/assets/member/styles/aaf5c053.proton.css">
    <link rel="stylesheet" href="/assets/member/styles/vendor/animate.css">
    <link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
    <link rel="stylesheet" href="/assets/external/chosen/chosen/chosen.css" media="screen"/>
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
    <script src="/assets/member/scripts/vendor/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/member/styles/6227bbe5.font-awesome.css" type="text/css"/>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css"/> -->
    <link rel="stylesheet" href="/assets/member/styles/40ff7bd7.font-titillium.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/member/css/member.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/css/notice.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/css/windows8.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/css/pis_ctr.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/css/image_gallery.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/plugin/dropzone/basic.css">
    <link rel="stylesheet" type="text/css" href="/assets/custom_plugin/myDropList/css/myDropList.css">
    <!-- <link rel="stylesheet" type="text/css" href="/assets/chartist/chartist.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="/assets/member/plugin/dropzone/dropzone.min.css"> -->
    <link rel="stylesheet" href="/assets/external/jquery_css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/assets/mlm/pace.css">
    <style type="text/css">
    a
    {
    cursor: pointer;
    }
    </style>
    <link rel="stylesheet" type="text/css" href="/assets/member/css/global.css?version=1">
    <link rel="stylesheet" type="text/css" href="/assets/member/css/item_add_v2.css">
    @yield('css')
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
    <script src="/assets/member/scripts/vendor/modernizr.js"></script>
    <script src="/assets/member/scripts/vendor/jquery.cookie.js"></script>
    <script>
    $(function()
    {
    $(".datepicker").datepicker();
    });
    </script>
    <!-- Ask luke pag may error -->
    <script src="/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <!-- polyfiller file to detect and load polyfills -->
    <script src="//cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
    <script>
    webshims.setOptions('waitReady', false);
    webshims.setOptions('forms-ext', {types: 'date'});
    webshims.polyfill('forms forms-ext');
    </script>
</head>
<body id="body">
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
    <div class="modal-loader hidden"></div>
    <!-- Modal -->
    <div class="modal fade" id="custom-modal" role="dialog">
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
                    <button type="button" class="btn btn-def-white btn-custom-white custom-button-close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary custom-button-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
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
    <!-- NAVIGATION -->
    <nav class="main-menu" data-step='2' data-intro='This is the extendable Main Navigation Menu.' data-position='right'>
        <!-- SIDEBAR MENU  -->
        @include('member.page')
        <ul class="logout">
            <li>
                <a href="/logout">
                    <i class="icon-off nav-icon"></i>
                    <span class="nav-text">
                        Logout
                    </span>
                </a>
            </li>
        </ul>
    </nav>
    <section class="wrapper extended scrollable">
        <nav class="user-menu">
            <a href="javascript:;" class="main-menu-access">
                <i class="icon-proton-logo"></i>
                <i class="icon-reorder"></i>
            </a>
            <section class="user-menu-wrapper">
                <a href="javascript:;" data-expand=".warehouse-view" class="warehouse-access" style="min-height: 50px; margin-top:25px;">
                    <span class="warehouse-access-name">{{$current_warehouse ? "Warehouse: ".$current_warehouse->warehouse_name : "No Warehouse"}}</span>
                </a>
                <a href="javascript:;" data-expand=".messages-view" class="messages-access">
                    <i class="icon-envelope-alt"></i>
                </a>
                <a href="javascript:;" data-expand=".notifications-view" class="notifications-access unread">
                    <i class="icon-comment-alt"></i>
                    <div class="menu-counter">{{$count_notification or 0}}</div>
                </a>
            </section>
            <div class="warehouse_loader_container">
                <div class="panel panel-default nav-view warehouse-view">
                    <div class="arrow user-menu-arrow"></div>
                    <div class="panel-heading">
                        <!-- <i class="icon-envelope-alt"></i> -->
                        <span>Choose a warehouse</span>
                        <a href="javascript:;" class="close-user-menu"><i class="icon-remove"></i></a>
                        
                        <div class="col-md-12" style="margin-top: 20px">
                            <select class="form-control select_current_warehouse">
                                @include('member.warehousev2.load_warehouse_v2_select',['_warehouse' => $_warehouse_list_shortcut])
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default nav-view messages-view">
                <div class="arrow user-menu-arrow"></div>
                <div class="panel-heading">
                    <i class="icon-envelope-alt"></i>
                    <span>Messages</span>
                    <a href="javascript:;" class="close-user-menu"><i class="icon-remove"></i></a>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="text-center">No Messages!</div>
                    </li>
                    <li class="list-group-item hidden">
                        <i><img src="" alt="User Icon"></i>
                        <div class="text-holder">
                            <span class="title-text">
                                Nunc Cenenatis:
                            </span>
                            <span class="description-text">
                                Hi, can you meet me at the office tomorrow morning?
                            </span>
                        </div>
                        <span class="time-ago">
                            3 mins ago
                        </span>
                    </li>
                   
                </ul>
            </div>
            <div class="panel panel-default nav-view notifications-view">
                <div class="arrow user-menu-arrow"></div>
                <div class="panel-heading">
                    <i class="icon-comment-alt"></i>
                    <span>Notifications</span>
                    <a href="javascript:;" class="close-user-menu"><i class="icon-remove"></i></a>
                </div>
                <ul class="list-group">
                    @if(count($_notification) > 0)
                        @foreach($_notification as $notif)
                        <li class="list-group-item popup" size="md" link="/member/transaction/notification/notification-message?id={{$notif->notification_id}}" >
                            <div class="text-holder">
                                <span class="title-text">
                                    {{ $notif->user_name }}
                                </span>
                                <span class="description-text">
                                    {!! $notif->notification_description !!}
                                </span>
                            </div>
                            <span class="time-ago">
                                {{time_ago($notif->created_date)}}
                            </span>
                        </li>
                        @endforeach
                    @else
                    <li class="list-group-item">
                        <div class="text-center">No Notification!</div>
                    </li>
                    @endif
                    <li class="list-group-item hidden">
                        <i><img src="" alt="User Icon"></i>
                        <div class="text-holder">
                            <span class="title-text">
                                Flor Demoa
                            </span>
                            <span class="description-text">
                                wrote a new post.
                            </span>
                        </div>
                        <span class="time-ago">
                            3 hrs ago
                        </span>
                    </li>
                    <li class="list-group-item hidden">
                        <i><img src="" alt="User Icon"></i>
                        <div class="text-holder">
                            <span class="title-text">
                                Nunc Neque
                            </span>
                            <span class="description-text">
                                wrote a new post.
                            </span>
                        </div>
                        <span class="time-ago">
                            57 mins ago
                        </span>
                    </li>
                    <li class="list-group-item hidden">
                        <i><img src="" alt="User Icon"></i>
                        <div class="text-holder">
                            <span class="title-text">
                                Flor Demoa
                            </span>
                            <span class="description-text">
                                submitted a new ticket.
                            </span>
                        </div>
                        <span class="time-ago">
                            1.5 hrs ago
                        </span>
                    </li>
                    <li class="list-group-item hidden">
                        <i><img src="" alt="User Icon"></i>
                        <div class="text-holder">
                            <span class="title-text">
                                Nunc Cenenatis
                            </span>
                            <span class="description-text">
                                wrote a new post.
                            </span>
                        </div>
                        <span class="time-ago">
                            3 hrs ago
                        </span>
                    </li>
                </ul>
            </div>
        </nav>
        <ol class="breadcrumb breadcrumb-nav">
            <li><a href="."><i class="icon-home"></i></a></li>
            <li class="active">
                <a class="bread-page-title" style="text-transform: lowercase;" data-toggle="dropdown" href="#">http://{{ $frontend_domain }}</a>
                <ul class="dropdown-menu dropdown-menu-arrow" role="menu">
                    <li role="presentation">
                        <a target="_blank" href="http://{{ $frontend_domain }}" tabindex="-1" role="menuitem">Visit My Shop</a>
                        <a href="#basic-table" tabindex="-1" role="menuitem">Change URL</a>
                        <a href="#basic-table" tabindex="-1" role="menuitem">Customize Shop</a>
                    </li>
                </ul>
            </li>
        </ol>
        @yield('content')
    </section>
    <div id="global_modal" class="modal fade" role="dialog" >
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content modal-content-global">
            </div>
        </div>
    </div>
    <!-- END GLOBAL MODAL -->
    <!-- GLOBAL MULTIPLE MODAL -->
    <div class="multiple_global_modal_container"></div>
    <!-- END GLOBAL MULTIPLE MODAL -->
    <!-- GFLOBAL IMAGE GALLERY -->
    <div id="ModalGallery" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Create Gallery </h4>
                    <ul class="nav nav-tabs">
                        <li class="cursor-pointer"><a class="cursor-pointer tab-upload-files" data-toggle="tab" href="#upload-files"><i class="fa fa-upload"></i> Upload Files</a></li>
                        <li class="active cursor-pointer"><a class="cursor-pointer tab-media-library" data-toggle="tab" href="#media-library"><i class="fa fa-picture-o"></i> Media Library</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade in" id="upload-files">
                        <div class="modal-body">
                            <div class="top-div clearfix">
                                <form action="/image/image_upload" id="myDropZone" class="dropzone" method="post" enctype="multipart/form-data">
                                    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
                                    <input type="file" name="file" id="myFile" multiple style="display: none"><br>
                                    <div class="dz-message">
                                        <span class="needsclick">
                                            <h1><b>DRAG & DROP</b></h1>
                                            <h4>your files here or click it to browse</h4>
                                        </span>
                                    </div>
                                    <!-- <input type="submit" value="Upload File to Server"> -->
                                </form>
                            </div>
                        </div>
                        <!-- <div class="modal-footer">
                            <div class="pull-right">
                                <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" >Upload</button>
                            </div>
                        </div> -->
                    </div>
                    <div class="tab-pane fade in active" id="media-library">
                        <div class="modal-body">
                            <div class="top-div clearfix">
                                <div class="row form-group col-md-3">
                                    <select class="form-control input-sm" name="" id="">
                                        <option name="" value="">Gallery Package 1</option>
                                        <option name="" value="">Gallery Package 2</opiton>
                                        </select>
                                    </div>
                                    <div class="row form-group col-md-4 pull-right">
                                        <input type="text" placeholder="Search" name="search" class="form-control input-sm">
                                    </div>
                                </div>
                                <div calss="middle-content">
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="image-wrapper pull-left">
                                    <div class="image-wrapper-info">
                                        <div><i class="selected">0 </i>&nbsp;<i> Selected</i></div>
                                        <div><a class="clear-all" href="javascript:">Clear</a></div>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <!-- <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button> -->
                                    <button type="button" class="btn btn-primary" id="get-selected-image">Get Selected Image</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="settings_checking hide" id="do_not_remove_please"></div>
        <style type="text/css">
        .blink-ctr
        {
        animation: blinker 1s linear infinite;
        }
        @keyframes blinker
        {
        50% { opacity: 0; }
        }
        </style>
        <script type="text/javascript" src="/assets/member/global.js?version=8.3"></script>
        <!-- Testing only -->
        <script type="text/javascript" src="/assets/member/settings/settings_global.js"></script>
        <script type="text/javascript" src="/assets/member/mlm/global_slot.js"></script>
        <!-- End -->
        <script src="/assets/external/jquery_ui/jquery_ui.js"></script>
        <script src="/assets/member/scripts/e1d08589.bootstrap.min.js"></script>
        <script src="/assets/member/scripts/9f7a46ed.proton.js"></script>
        <script type="text/javascript" src="/assets/member/scripts/vendor/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
        <!--<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js'></script>-->
        <script type="text/javascript" src='/assets/member/js/notice.js'></script>
        <script type="text/javascript" src='/assets/member/js/pis_counter.js?version=1'></script>
        <script type="text/javascript" src='/assets/member/js/accounting_transaction/barcode.js'></script>
        <script type="text/javascript" src='/assets/member/js/accounting_transaction/check_tax.js'></script>
        <script type="text/javascript" src="/assets/external/chosen/chosen/chosen.jquery.js"></script>
        <script type="text/javascript" src='/assets/member/plugin/dropzone/dropzone.js'></script>
        <script type="text/javascript" src='/assets/member/js/image_gallery.js'></script>
        <script type="text/javascript" src='/assets/custom_plugin/myDropList/js/myDropList.js'></script>
        <script type="text/javascript" src="/assets/member/js/prompt_serial_number.js"></script>
        <script type="text/javascript" src="/assets/member/js/accounting_transaction.js"></script>
        <script type="text/javascript" src='/assets/member/js/match-height.js'></script>
        <script type="text/javascript" src='/assets/chartjs/Chart.bundle.min.js'></script>
        <script type="text/javascript" src="/assets/mlm/pace.min.js"></script>
        <script type="text/javascript">
        $(document).ajaxStart(function() { Pace.restart(); });
        $(document).ajaxComplete( function (){
        // webshims.setOptions('waitReady', false);
        // webshims.setOptions('forms-ext', {types: 'date'});
        // webshims.polyfill('forms forms-ext');
        // $(function() { $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' }); });
        });
        $('.select_current_warehouse').click(function(event)
        {
            event.stopPropagation();
        });
        $('.select_current_warehouse').globalDropList({
            hasPopup : 'false',
            width : '250px', 
            onChangeValue : function()
            {
                select_current_warehouse($(this));
            }
        })

         $('.select_warehouse').click(function(event)
        {
            event.stopPropagation();
        });
        $('.select_warehouse').globalDropList({
            hasPopup : 'false',
            width : '250px', 
            // onChangeValue : function()
            // {
            //     select_warehouse($(this));
            // }
        })
        function show_currency()
        {
            $('.change_currency').each(function()
            {
                var amount = $(this).html();
                var currency_change = formatPHP(amount);
                $(this).html(currency_change);
            });
        }
        show_currency();
        function formatPHP(num) {
        var num2 = parseFloat(num);
        var p = num2.toFixed(2).split(".");
        return "PHP " + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
        }, "") + "." + p[1];
        }
        </script>
        <script type="text/javascript" src="/assets/member/js/jquery-ui.js"></script>
        <script src="/email_assets/js/list.js"></script>
        @yield('script')
    </body>
</html>
