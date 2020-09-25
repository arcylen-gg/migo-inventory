<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <title>Digima Web Solutions, Inc.</title>
        <!-- Path to Framework7 Library CSS-->
        <link rel="stylesheet" href="/assets/mobile/framework7/dist/css/framework7.ios.min.css">
        <!-- Path to your custom app styles-->
        <link rel="stylesheet" href="/assets/super/css/super.css">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="statusbar-overlay"></div>
        <div class="panel-overlay"></div>
        <div class="panel panel-left panel-reveal sidebar">
            <div class="sidebar-title">NAVIGATION</div>
            <div class="sidebar-link-container content-block">
                <div class="sidebar-link">
                    <div class="sidebar-icon"><i class="fa fa-handshake-o"></i></div>
                    <a class="close-panel" href="/super/client">Manage Clients</a>
                </div>
                <div class="sidebar-link">
                    <div class="sidebar-icon"><i class="fa fa-user-secret"></i></div>
                    <a class="close-panel" href="/super/admin">Manage Super Admin</a>
                </div>
                <div class="sidebar-link">
                    <div class="sidebar-icon"><i class="fa fa-close"></i></div>
                    <a class="close-panel" href="javascript:" onclick="window.location.href='/super/logout'">Logout</a>
                </div>
            </div>
        </div>
        <div class="views">
            <div class="view view-main">
                <div class="navbar">
                    <div data-page="index" class="navbar-inner">
                        <div class="left">
                            <a data-panel="right" href="#" class="link icon-only open-panel"><i class="icon icon-bars"></i></a>
                        </div>
                        <div class="center sliding">ADMIN PANEL</div>
                    </div>
                </div>
                <div class="pages navbar-through toolbar-through">
                    <div data-page="index" class="page">
                        <div class="page-content dashboard-content">


                            <div class="card">
                                <div class="card-header">Recent Client Activities <a href=""><i class="fa fa-refresh"></i></a></div>
                                <div class="card-content">
                                    <div class="card-content-inner">
                                        <div class="activity-container">
                                            <div class="activity">
                                                <div class="detail">
                                                    <b>Guillermo Tabligan</b> edited detail of a customer in Reward Slots V2.
                                                </div>
                                                <div class="subdetail">
                                                    <div class="time-ago">2 minutes ago</div>
                                                    <div class="client">3EXCEL</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-content-inner">
                                        <div class="activity-container">
                                            <div class="activity">
                                                <div class="detail">
                                                    <b>Guillermo Tabligan</b> edited detail of a customer in Reward Slots V2.
                                                </div>
                                                <div class="subdetail">
                                                    <div class="time-ago">2 minutes ago</div>
                                                    <div class="client">3EXCEL</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="toolbar tabbar tabbar-labels">
                    <div class="toolbar-inner">
                        <a class="tab-link active dashboard" href="javascript: mainView.router.back({pageName:'index',force:true});  myApp.closePanel();">
                            <i class="icon nav-icon fa fa-home"></i>
                            <span class="tabbar-label">Dashboard</span>
                        </a>
                        <a href="#" class="tab-link message">
                            <i class="icon nav-icon fa fa-envelope">
                            {{-- <span class="badge bg-red">5</span> --}}
                            </i>
                            <span class="tabbar-label">Message</span>
                        </a>
                        <a href="#" class="tab-link mytask">
                            <i class="icon nav-icon fa fa-list"></i>
                            <span class="tabbar-label">My Task</span>
                        </a>
                        <a href="#" class="tab-link myaccount">
                            <i class="icon nav-icon fa fa-gear"></i>
                            <span class="tabbar-label">My Account</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="/assets/mobile/framework7/dist/js/framework7.min.js"></script>
        <script type="text/javascript" src="/assets/admin/super.js?v=2.0"></script>
    </body>
</html>