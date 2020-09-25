<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7 lt-ie10"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8 lt-ie10"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My 168 Shop</title>
    <meta name="description" content="Page Description">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="/assets/member/styles/92bc1fe4.bootstrap.css">
    <link rel="stylesheet" href="/assets/member/css/setup.css">
    <link rel="stylesheet" href="/assets/member/styles/6227bbe5.font-awesome.css" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/member/styles/40ff7bd7.font-titillium.css" type="text/css"/>
</head>
<body>
<div class="container">

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <form method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <h2>Add an address <small>to setup currencies and tax rates.</small></h2>
            @if(session()->has('message'))
                <span class="member" style="color: red;">
                     <strong>Error!</strong> {{ session('message') }}<br>
                </span>
            @endif
            <hr class="colorgraph">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>First Name</label>
                        <input value="{{ old('first_name') }}" type="text" name="first_name" id="first_name" class="form-control input-lg"  tabindex="1">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input value="{{ old('last_name') }}" type="text" name="last_name" id="last_name" class="form-control input-lg" tabindex="2">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Street Address</label>
                <input value="{{ old('street_address') }}" type="text" name="street_address" id="street_address" class="form-control input-lg" tabindex="3">
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>City</label>
                        <input value="{{ old('city') }}" type="text" name="city" id="city" class="form-control input-lg"  tabindex="5">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>Zip/Postal Code</label>
                        <input value="{{ old('postal_code') }}" type="text" name="postal_code" id="postal_code" class="form-control input-lg" tabindex="6">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>Country</label>
                        <select name="country" class="form-control input-lg" tabindex="7">
                            @foreach($_country as $country)
                            <option {{ old('country') ==  $country->country_id ? 'selected=selected' : '' }} name="country" value="{{ $country->country_id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input value="{{ old('contact_number') }}" type="text" name="contact_number" id="contact_number" class="form-control input-lg" tabindex="8">
                    </div>
                </div>
            </div>

            
            <hr class="colorgraph">
            <div class="row">
                <div class="col-xs-12 col-md-6"></div>
                <div class="col-xs-12 col-md-6">
                    <button type="submit" href="#" class="btn btn-primary btn-block btn-lg">Enter My Store &raquo;</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

</body>
</html>

