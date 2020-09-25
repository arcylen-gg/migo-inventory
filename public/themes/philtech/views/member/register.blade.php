@extends("layout")
@section("content")

<form method="post">
    {{ csrf_field() }}
    <div class="content member-mob-margin">
        <div class="container">
            <input type="hidden" id="_token" value="{{csrf_token()}}">
            <div class="register">
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <div class="register-header clearfix">
                                    <div class="left">
                                        <div class="title">Create new customer account</div>
                                    </div>
                                    <div class="right">
                                        <div class="or">
                                            <img src="/themes/{{ $shop_theme }}/img/or-2.png">
                                        </div>
                                        <div class="text-right social-button">
                                            <a href="{{$fb_login_url or '#'}}" class="holder fb">
                                                <!--<div class="name"><i class="fa fa-facebook" aria-hidden="true"></i> Sign up with Facebook</div>-->
                                            </a>
                                            <a href="javascript:" class="holder gp" id="customBtn">
                                                <div class="name"><i class="fa fa-google-plus" aria-hidden="true"></i> Sign up with Google+</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="c1">
                                <div class="register-form">
                                    <div class="form-group">
                                        <div class="register-label">GENDER</div>
                                        <div class="form-input gender-input">
                                            <label class="radio-inline"><input checked type="radio" name="gender" value="male">MALE</label>
                                            <label class="radio-inline"><input type="radio" name="gender" value="female">FEMALE</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="register-label">EMAIL</div>
                                        <div class="form-input">
                                            <input required class="form-control input-sm" type="email" name="email" placeholder="Type Your Email Here" value="{{ $dummy['email'] or old('email') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="register-label">CUSTOMER NAME</div>
                                        <div class="form-input">
                                            <input required class="form-control input-sm" type="text" name="first_name" placeholder="First Name" value="{{ $dummy['first_name'] or old('first_name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-input">
                                            <input class="form-control input-sm" type="text" name="middle_name" placeholder="Middle Name" value="{{ $dummy['middle_name'] or old('middle_name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-input">
                                            <input requred class="form-control input-sm" type="text" name="last_name" placeholder="Last Name" value="{{ $dummy['last_name'] or old('last_name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="register-label">BIRTHDAY</div>
                                        <div class="form-input">
                                            <div class="date-holder">
                                                <select name="b_month" class="form-control">
                                                    @for($ctr = 1; $ctr <= 12; $ctr++)
                                                    <option {{ old('b_month') == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ date("F", strtotime($ctr . "/01/17")) }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="date-holder">
                                                <select name="b_day" class="form-control">
                                                    @for($ctr = 1; $ctr <= 31; $ctr++)
                                                    <option {{ old('b_day') == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ $ctr }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="date-holder">
                                                <select name="b_year" class="form-control">
                                                    @for($ctr = date("Y"); $ctr >= (date("Y")-100); $ctr--)
                                                    <option {{ old('b_year') == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ $ctr }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="c2">
                                
                                <div class="register-form">
                                    @if(Session::has('error'))
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>{!! session('error') !!}</li>
                                        </ul>
                                    </div>
                                    @endif
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    
                                    <div class="form-group">
                                        <div class="register-label">CONTACT NUMBER</div>
                                        <div class="form-input">
                                            <input required class="form-control input-sm" type="text" name="contact" value="{{ $dummy['contact'] or old('contact') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="register-label">PASSWORD</div>
                                        <div class="form-input">
                                            <input required class="form-control input-sm" type="password" name="password" value="{{ $dummy['password'] or '' }}">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="register-label">REPEAT PASSWORD</div>
                                        <div class="form-input">
                                            <input required class="form-control input-sm" type="password" name="password_confirmation" value="{{ $dummy['password'] or '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group clearfix" style="margin-top: 15px;">
                                        <div class="checkbox agreement-checkbox">
                                            <label><input type="checkbox" value="" required> I agree to the <span>Franchise Terms and Condition</span></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="choice">
                                            <div class="holder">
                                                <button class="btn btn-custom">Sign Up</button>
                                            </div>
                                            <div class="holder"><span class="or">OR</span></div>
                                            <div class="holder"><a class="login-href" href="/members/login">Login an Account</a></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>            
    </div>
</form>
<!-- Modal -->
<div class="modal fade modal-agreement" id="modal_agreement" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">Accept Philtech Franchise Terms and Conditions</div>
            <div class="modal-body">
                <div class="contract">
                    {!! get_content($shop_theme_info, "terms_and_conditions", "franchise_terms_and_condition") !!}
                </div>
            </div>
            <div class="modal-footer">
            	<button type="submit" class="btn btn-pure pull-right" data-dismiss="modal">Accept</button>
                <button type="submit" class="btn btn-semi pull-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section("js")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/member_register.js"></script>
<script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>
<script>startApp();</script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_register.css?version=1.2">
@endsection