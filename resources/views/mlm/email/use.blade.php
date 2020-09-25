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

        <link rel="stylesheet" href="/assets/external/chosen/chosen/chosen.css" media="screen"/>

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
                            <form method="post" action="/mlm/membership_active_code/active/code" class="global-submit use_form" autocomplete="on"> 
                            <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
                                <div class="lol-title"><span>Use</span> Code</div>

                                @if(isset($membership_code->customer_id))
                                {!! $customer !!}
                                <p> 
                                    <label>Membership Code</label>
                                    <input id="username" name="membership_activation_code" required="required" type="text" value="{{$membership_code->membership_activation_code}}" readonly />
                                </p>
                                <p> 
                                    <label>Membership Pin</label>
                                    <input id="username" name="membership_code_id" required="required" type="text" value="{{$membership_code->membership_code_id}}" readonly />
                                </p>
                                <p>
                                    <label>Password</label>
                                    <input type="password" name="password" placeholder="***">
                                </p>
                                <p>
                                        <label>Sponsor</label>
                                        @if($lead == null)
                                        <label for="">Sponsor</label>
                                            <select class="form-control chosen-slot_sponsor input-sm pull-left select_country" name="slot_sponsor" data-placeholder="Select Slot Sponsor" >
                                                @if(count($_slots) != 0)
                                                    @foreach($_slots as $slot)
                                                        <option value="{{$slot->slot_id}}">{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} ({{$slot->slot_no}})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        @else
                                        <input type="hidden" name="lead_id" value="{{$lead->lead_id}}">
                                        <input type="hidden" name="slot_sponsor" value="{{$lead->lead_slot_id_sponsor}}">
                                        <input type="text" class="form-control" name="sponsor" value="{{$lead->mlm_username}} (Slot - {{$lead->slot_no}}) {{$lead->membership_activation_code}}" readonly>
                                        @endif
                                    @if(isset($binary_settings->marketing_plan_enable))
                                        @if($binary_settings->marketing_plan_enable == 1)
                                            @if(isset($binary_advance->binary_settings_placement))
                                                @if($binary_advance->binary_settings_placement == 0)
                                                    <div class="col-md-12">
                                                        <label for="">Slot Placement (Binary)</label>
                                                        <select name="slot_placement" class="form-control">
                                                        @if(count($_slots) != 0)
                                                            @foreach($_slots as $slot)
                                                                <option value="{{$slot->slot_id}}">{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} ({{$slot->slot_no}})</option>
                                                            @endforeach
                                                        @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="">Slot Position</label>
                                                        {!! mlm_slot_postion('left') !!}
                                                    </div>
                                                @elseif($binary_advance->binary_settings_placement == 1)

                                                    <div class="col-md-12">
                                                        <label for="">Slot Placement (Binary) <span style="color: red;">Auto Placement</span></label>
                                                        <input type="text" class="form-control" name="slot_placement" disabled="disabled">
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="">Slot Position <span style="color: red;">Auto Placement</span></label>
                                                        <select class="form-control chosen-slot_position " name="slot_position" disabled="disabled">
                                                            <option value="left">left</option>
                                                            <option value="right">right</option>
                                                        </select>
                                                    </div>
                                                @endif
                                            @else
                                                <center></center>
                                            @endif
                                        @else

                                        @endif
                                    @else
                                        <center></center>
                                    @endif
                                </p>
                                <p>
                                    <hr>
                                </p>
                                <p class="register button" >
                                @if($membership_code->used == 0)
                                 <input type="button" onclick="use_mem_button()" value="Use Membership Code" /> 
                                @else
                                <center>Code Already Used.</center>
                                @endif 
                                </p>
                                @else
                                <center>Invalid Membership Code</center>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>  
            </section>
        </div>
        <script type="text/javascript" src="/assets/member/global.js"></script>
        <script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
        <script type="text/javascript" src="/assets/external/chosen/chosen/chosen.jquery.js"></script>
        <script type="text/javascript">
        function submit_done(data)
        {
           if(data.status == 'warning')
           {
            toastr.warning(data.message);
           }
           if(data.response_status == "warning")
            {
                var erross = data.warning_validator;
                $.each(erross, function(index, value) 
                {
                    toastr.warning(value);
                }); 
            }
            else if(data.response_status == 'warning_2')
            {
                toastr.warning(data.error);
            }
            else if(data.response_status == 'success_add_slot')
            {
                toastr.success('Congratulations, Your slot is created.');
                toastr.success('Please, Login again for the changes to take effect');
                window.location = "/mlm";
            }
        }
        function use_mem_button()
        {
            $('.use_form').submit();
        }
         $('select').chosen({
                search_contains: true
            });  
        </script>
    </body>
</html>