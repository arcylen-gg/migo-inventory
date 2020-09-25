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
    
    <link rel="stylesheet" href="{{ public_path().'/assets/member/styles/92bc1fe4.bootstrap.css' }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">  
    <link rel="stylesheet" type="text/css" href="{{public_path()}}/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{public_path()}}/assets/member/styles/6227bbe5.font-awesome.css" type="text/css"/>
    <style type="text/css">
        .info-box{
                display: block;
                min-height: 90px;
                background: #fff;
                width: 100%;
                box-shadow: 0 1px 1px rgba(0,0,0,0.1);
                border-radius: 2px;
                margin-bottom: 15px;
        }
        .info-box-number
        {
                display: block;
                font-weight: bold;
                font-size: 18px;
        }
        .info-box-icon
        {
                border-top-left-radius: 2px;
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 2px;
                display: block;
                float: left;
                height: 90px;
                width: 90px;
                text-align: center;
                font-size: 45px;
                line-height: 90px;
                background: rgba(0,0,0,0.2);
        }
        .bg-primary
        {
            background-color: #76b6ec !important;
        }
        .info-box-text
        {
            display: block;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .info-box-content
        {
                padding: 5px 10px;
                margin-left: 90px;
        }
        thead, tfoot { display: table-row-group }
    </style>
    @yield('css')
</head>
<body id="body">
    @yield('body')
</body>
</html>