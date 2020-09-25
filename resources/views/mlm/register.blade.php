<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Login and Registration Form with HTML5 and CSS3" />
        <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="/resources/assets/login/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="/resources/assets/login/css/style2.css" />
        <link rel="stylesheet" type="text/css" href="/resources/assets/login/css/animate-custom.css" />
        <!-- CUSTOM CSS -->
        <link rel="stylesheet" type="text/css" href="/resources/assets/new_front/css/login.css">

        <!-- CUSTOM CSS -->
        <link rel="stylesheet" href="/assets/external/jquery_css/jquery-ui.css">
        <link rel="stylesheet" href="/assets/member/styles/92bc1fe4.bootstrap.css">
        <link rel="stylesheet" href="/assets/member/styles/vendor/jquery.pnotify.default.css">
        <link rel="stylesheet" href="/assets/member/styles/vendor/select2/select2.css">
        <link rel="stylesheet" href="/assets/member/styles/vendor/datatables.css" media="screen"/> 
        <link rel="stylesheet" href="/assets/member/styles/aaf5c053.proton.css">
        <link rel="stylesheet" href="/assets/member/styles/vendor/animate.css">
        <link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
        <link rel="stylesheet" href="/assets/external/chosen/chosen/chosen.css" media="screen"/>


        <link rel="stylesheet" type="text/css" href="/resources/assets/new_front/css/login.css">
        <link rel="stylesheet" type="text/css" href="/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/member/styles/6227bbe5.font-awesome.css" type="text/css"/>
        <link rel="stylesheet" href="/assets/member/styles/40ff7bd7.font-titillium.css" type="text/css"/>
        <link rel="stylesheet" href="/assets/member/css/member.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/css/notice.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/css/windows8.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/css/image_gallery.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/plugin/dropzone/basic.css">
        <link rel="stylesheet" type="text/css" href="/assets/custom_plugin/myDropList/css/myDropList.css">
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
        .select_country
        {
            background-color: #fff;
    box-shadow: none;
    border: 1px solid #adadad;
    border-radius: 25px;
    height: 45px;
    box-sizing: border-box;
    width: 100%;
        }
        </style>
    </head>
    <body style="background-image: url('/resources/assets/new_front/img/login-bg.jpg'); overflow: auto;">
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
        <div class="container">
            <header>
                <nav class="codrops-demos" style="text-align: center;">
                </nav>
            </header>
            <section>               
                <div id="container_demo" >
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>

                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form method="post" action="/mlm/register" class="global-submit" autocomplete="on"> 
                            <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
                                <div class="lol-title"><span>Members</span> Register</div>
                                <p> 
                                    <input id="username" name="first_name" required="required" type="text" placeholder="Your First Name"/>
                                </p>
                                <p> 
                                    <input id="username" name="last_name" required="required" type="text" placeholder="Your Last Name"/>
                                </p>
                                <p> 
                                    <input id="username" name="company" type="text" placeholder="Company (Optional)"/>
                                </p>
                                <p> 
                                    <input id="username" name="email" required="required" type="email" placeholder="Your Email"/>
                                </p>
                                <p>
                                    <input type='number' class="form-control" name="customer_mobile" value="639">
                                </p>
                                <p> 
                                    <input id="tinnumber" name="tinnumber" type="number" placeholder="Your TIN (Optional)"/>
                                </p>
                                <p> 
                                    <input id="username" name="username" required="" type="text" placeholder="Your Username"/>
                                </p>
                                <p> 
                                    <input id="password" name="pass" required="required" type="password" placeholder="Password" /> 
                                </p>
                                <p> 
                                    <input id="password" name="pass2" required="required" type="password" placeholder="Confirm Password" /> 
                                </p>
                                
                                
                                <p>
                                    <input id="first-name" name="customer_street" required="required" class="form-control"  value="" placeholder="Street">
                                </p>
                                <p>
                                    <input id="first-name" name="customer_city" required="required" class="form-control" value="" placeholder="City">
                                </p>
                                <p>
                                    <input id="first-name" name="customer_state" required="required" class="form-control"  value="" placeholder="Province">
                                </p>
                                <p>
                                    <input type="number" id="first-name" name="customer_zipcode" required="required" class="form-control"  value="" placeholder="Zip Code">
                                </p>
                                <p> 
                                    <select class="form-control select_country" name="country" style="">
                                        @foreach($country as $value)
                                            <option value="{{$value->country_id}}" @if($value->country_id == 420) selected @endif >{{$value->country_name}}</option>
                                        @endforeach
                                    </select>
                                </p>
                                @if($lead == null)
                                <p>
                                    <input id="username" name="membership_code" onchange="get_sponsor_info_via_membership_code(this)"  type="text" placeholder="Membership Code of Sponsor (Optional) "/>
                                </p>
                                <p class="sponsor-info " id="sponsor_info_get">
                                    
                                </p>
                                @else
                                <p>
                                    <center>Sponsor</center>
                                    <select class="form-control select_country" name="membership_code" style="">
                                        @foreach($lead_code as $value)
                                            <option value="{{$value->membership_activation_code}}" >{{$value->membership_activation_code}} (Slot {{$value->slot_no}})</option>
                                        @endforeach
                                    </select>
                                </p>
                                <p class="sponsor-info" id="sponsor_info_get" >
                                        @if(isset($customer_info)){!! $customer_info !!}@endif
                                </p>
                                @endif


                                <p class="login button"> 
                                    <input type="submit" value="Sign Up" class="butonn_register" onclick="click_submit_button(this)" /> 
                                </p>
                                <div class="divider-holder" style="margin-bottom: 15px;">
                                    <div class="divider"></div>
                                    <span>Already have an account?</span>
                                </div>
                                <p class="register button"> 
                                    <input type="button" value="Login an Account" onClick="location.href='/mlm/login'" /> 
                                </p>
                            </form>
                        </div>
                    </div>
                </div>  
            </section>
        </div>
        <script type="text/javascript" src="/assets/member/global.js"></script>
        <script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
        <script type="text/javascript">
        function click_submit_button(ito)
        {
            $('.global-submit').submit();
            $('.butonn_register').attr("disabled", true);
        }
        
        function submit_done(data)
        {
            $('.butonn_register').prop("disabled", false);
            // $('.butonn_register').removeAttr("disabled");
            console.log(data);
            if(data.type == 'error')
            {
                toastr.error(data.message);
                $('.butonn_register').attr("disabled", false);
                $('.butonn_register').removeAttr("disabled");
            }
            else
            {
                $('.butonn_register').attr("disabled", true);
                toastr.success(data.message);
                location.href = '/mlm';
            }
        }
        function get_sponsor_info_via_membership_code(ito)
        {
            var membership_code = $(ito).val();
            get_customer_view(membership_code);
        }
        function get_customer_view(membership_code)
        {
            $('#sponsor_info_get').load('/mlm/register/get/membership_code/' + membership_code);
        }
        </script>
    </body>
</html>