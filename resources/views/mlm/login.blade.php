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
    <body style="background-image: url('/resources/assets/new_front/img/login-bg.jpg')">
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
                            <form method="post" action="/mlm/login" class="global-submit" autocomplete="on"> 
                            <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
                                <div class="lol-title"><span>Members</span> Login</div>
                                <p> 
                                    <input id="username" name="user" required="required" type="text" placeholder="Username"/>
                                </p>
                                <p> 
                                    <input id="password" name="pass" required="required" type="password" placeholder="Password" /> 
                                </p>
                                <p class="login button"> 
                                    <input type="submit" value="Login" > 
                                </p>
                                @if($register_button == 1)
                                    <div class="divider-holder" style="margin-bottom: 15px;">
                                        <div class="divider"></div>
                                        <span>Don't have an account yet?</span>
                                    </div>
                                    <p class="register button"> 
                                        <input type="button" value="Create an Account" onClick="location.href='/mlm/register'" /> 
                                    </p>
                                @endif
                                <p>
                                    <a href="/mlm/login/forgot_password" style="text-decoration: none">Forgot Password ?</a>
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
        function submit_done(data)
        {
            console.log(data);
            if(data.type == 'error')
            {
                toastr.error(data.message);
            }
            else
            {
                toastr.success(data.message);
                location.href = '/mlm';
            }
        }
        </script>
    </body>
</html>