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
    <link rel="stylesheet" type="text/css" href="/assets/member/css/image_gallery.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/plugin/dropzone/basic.css">
    <link rel="stylesheet" type="text/css" href="/assets/custom_plugin/myDropList/css/myDropList.css">

    <!-- <link rel="stylesheet" type="text/css" href="/assets/chartist/chartist.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="/assets/member/plugin/dropzone/dropzone.min.css"> -->

    <link rel="stylesheet" href="/assets/external/jquery_css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/assets/mlm/pace.css">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                @if ($message = Session::get('success'))
                <div class="custom-alerts alert alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('success');?>
                @endif
                @if ($message = Session::get('error'))
                <div class="custom-alerts alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('error');?>
                @endif
                <div class="panel-heading">Paywith Dragonpay</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="/payment/dragonpay" >

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group{{ $errors->has('txnid') ? ' has-error' : '' }}">
                            <label for="txnid" class="col-md-4 control-label">Reference No.</label>
                            <div class="col-md-6">
                                <input id="txnid" type="text" class="form-control" name="txnid" value="{{ old('txnid') }}" onkeyup="keyGenerate()" autofocus>
                                @if ($errors->has('txnid'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('txnid') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                       

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Amount</label>
                            <div class="col-md-6">
                                <input id="amount" type="text" class="form-control" name="amount" value="{{ old('amount') }}" onkeyup="keyGenerate()" autofocus>
                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('ccy') ? ' has-error' : '' }}">
                            <label for="ccy" class="col-md-4 control-label">Currency</label>
                            <div class="col-md-6">
                                <select class="form-control m-bot15" id="ccy" name="ccy" onchange="keyGenerate()">
                                    <option value="PHP" {{ (old('ccy') == 'PHP') ? 'Selected' : '' }} >PHP - Philippine Peso</option>
                                    <option value="USD" {{ (old('ccy') == 'USD') ? 'Selected' : '' }}>USD - US Dollar</option>
                                    <option value="CAD" {{ (old('ccy') == 'CAD') ? 'Selected' : '' }}>CAD - Candadian Dollar</option>
                                </select>
                                @if ($errors->has('ccy'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ccy') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Product Description</label>
                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" autofocus>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Customer Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Paywith Dragonpay
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

