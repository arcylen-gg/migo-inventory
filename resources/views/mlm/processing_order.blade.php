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
  <title>Still processing...</title>
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
<body>
   <div class="panel panel-default" style="text-align:center;">
      @if($first_order)
      <div class="panel-heading">Your order status: {{$first_order->order_status}}</div>
      <div class="panel-body">Order Number:{{$first_order->ec_order_id}}</div>
      <div class="panel-body">Customer Email:{{$first_order->customer_email}}</div>
<!--       <div class="panel-body">Order Status:{{$first_order->order_status}}</div> -->
      <div class="panel-body">Payment Status:{{$first_order->payment_status == 1 ? 'Paid' : 'Unpaid'}}</div>
      <div class="panel-body">Billing Adress:{{$first_order->billing_address}}</div>
      <div class="panel-body"><a href="mlm/login">Click here</a> to logout</div>
      @else
      <div class="panel-heading">No slot</div>
      @endif
   </div>
</body>
