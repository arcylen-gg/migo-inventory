@extends("layout")
@section("content")
<form method="post">
{{ csrf_field() }}
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
										<input class="form-control input-sm" type="email" name="email" placeholder="Type Your Email Here" value="{{ $dummy['email'] or old('email') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="register-label">CUSTOMER NAME</div>
									<div class="form-input">
										<input class="form-control input-sm" type="text" name="first_name" placeholder="First Name" value="{{ $dummy['first_name'] or old('first_name') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="form-input">
										<input class="form-control input-sm" type="text" name="middle_name" placeholder="Middle Name" value="{{ $dummy['middle_name'] or old('middle_name') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="form-input">
										<input class="form-control input-sm" type="text" name="last_name" placeholder="Last Name" value="{{ $dummy['last_name'] or old('last_name') }}">
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
										<input class="form-control input-sm" type="text" name="contact" value="{{ $dummy['contact'] or old('contact') }}">
									</div>
								</div>
								
								<div class="form-group">
									<div class="register-label">PASSWORD</div>
									<div class="form-input">
										<input class="form-control input-sm" type="password" name="password" value="{{ $dummy['password'] or '' }}">
									</div>
								</div>
								
								<div class="form-group">
									<div class="register-label">REPEAT PASSWORD</div>
									<div class="form-input">
										<input class="form-control input-sm" type="password" name="password_confirmation" value="{{ $dummy['password'] or '' }}">
									</div>
								</div>
								<div class="form-group clearfix" style="margin-top: 15px;">
									<div class="checkbox agreement-checkbox">
									  <label><input type="checkbox" value="" required> I agree to the Brown <span>Terms of Use and Privacy Policy</span></label>
									</div>
								</div>
								<div class="form-group">
									<div class="choice">
										<div class="holder">
											<button class="btn btn-brown">Sign Up</button>
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
</form>

<!-- Modal -->
<div class="modal fade modal-agreement" id="modal_agreement" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">Accept Brown Contract</div>
            <div class="modal-body">
                <div class="contract">BROWN CONTRACT

1. I am 18 years of age or older, with the legal capacity and personality to enter into this contract by and between myself and MySolid Technologies and Devices Corporation (hereinafter referred to as “Brown”).
2. By undergoing the registration process and clicking “______” on the Registration Page of Brown for the Brown Movement as defined herein, I acknowledge that I am applying to be an independent contractor of Brown to promote the purchase and use of mobile telephone products, accessories and related online services of Brown and such other products as Brown may include (the “Brown Products”),  in accordance with the Brown Reward Plan which constitutes an integral part of this Brown Contract (the “Brown Movement”).  
3.  I agree and acknowledge that Brown has the sole discretion to accept or not accept (without need to provide any reason) my membership in the Brown Movement.  This Brown Contract shall be deemed accepted by Brown at its sole discretion and upon my receipt of confirmation of such acceptance by Brown, in accordance with the Brown Policies and Procedures which constitute an integral part of this Brown Contact.  Upon my receipt of such confirmation, I shall thereby be considered a member of the Brown Movement (a “Brown Member”).
4. I agree to be bound and to abide by the terms and conditions set forth in this Brown Contract, the Brown Reward Plan and the Brown Policies and Procedures and such revisions, supplements, and other amendments as may from time-to-time be made thereon (collectively the “Brown Documents”). 
5. No promises, representations, guarantees or agreements of any kind, shall be deemed to have been made by Brown other than those expressly made in and written on the Brown Documents. 
6.  I acknowledge and agree that any violation by me of the Brown Contract or any provision of and my obligations under, the Brown Documents, may result in sanctions or penalties relating to my membership in the Brown Movement or termination of this Brown Contract, as deemed appropriate by Brown at its sole and absolute discretion.  
7. I understand that to become a Brown Member, I must purchase any of the qualifying Brown Products..
8. My Brown Membership and the Brown Membership of any other Brown Member which I recruited shall at all times be subject to the terms and conditions of this Brown Contract.
9. If I wish to terminate my Brown Membership, I shall deliver written notification thereof to Brown and such termination shall be effective as of the date of receipt of said notice by Brown.
10. I agree and acknowledge that as a Brown Member, I am an independent contractor. I am not an employee, partner, agent, joint venturer or legal representative of Brown. I agree that I am solely responsible for my compliance with any and all laws or regulations applicable to me and my business in any jurisdiction whatsoever, including but not limited to laws, rules and regulations which require any license or permit to operate and those which require the payment of taxes. I shall obey any and all national and local laws, statutes, and regulations applicable to me and my business.
11. While Brown or any of its affiliated entities or representatives may assist me in complying with applicable laws, rules and requirements, the conduct of my Brown Membership rests solely upon me. Therefore, I release and forever discharge Brown and any of its affiliated entities and their officers, directors, stockholders, employees, agents and representatives (collectively the “Brown Affiliates”), from any and all liabilities arising from my acts and/or omissions. I also waive any claims or causes of action that either I or others acting in my interest may have arising from my action/s as a Brown Member and hereby agree to indemnify and hold free and harmless Brown and the Brown Affiliates from any claim, action or liability arising out of my acts and omissions. 
12. I understand that only Brown Members considered by Brown to be in good standing may participate in the Brown Movement, including but not limited to recruitment of other Brown Members.
13. I understand and agree that I am responsible for training and supporting any Brown Member who I will recruit to be part of the Brown Movement (the “Brown Recruits”). I shall train all Brown Recruits who are accepted to become Brown Members in the recruitment of other Brown Recruits and the performance of their obligations under the Brown Documents,  exert best commercial efforts to supervise and monitor activities of Brown Recruits related to the Brown Movement, and shall promptly report to Brown any violation of this Brown Contract by any Brown Recruit that shall come to my attention.
14. I understand and agree that the Brown Movement is primarily a multi-level marketing program designed for the marketing and sale of the Brown Products and any Reward which I receive arising from membership of my Brown Recruits and their recruits are portions of the price paid by the said recruits for purchasing Brown Products and shall not be deemed or considered payment for their membership in the Brown Movement.  
15.  I shall not charge or collect from any Brown Recruit or any other person, any fee or amount in connection with the Brown Movement, including but not limited to fees or charges for their membership therein, and any violation of this provision shall be deemed a serious breach of this Brown Contract that shall result in the termination hereof and my membership in the Brown Movement, and forfeiture of any and all amounts to which I am entitled under this  Brown Contract including all rewards due to me, without prejudice to any claim for payment of damages or any other action arising from such breach.  
16. I agree not to alter, re-package, re-label, or otherwise change any Brown Product, or sell or market any Brown Product under any name or label other than that authorized by Brown. I further agree that I will refrain from creating, producing and using any plan, program, writing, recording or other materials, unless expressly approved or provided by Brown.
17. I understand and agree that I may not convey, assign or otherwise transfer any of my rights and/or obligations under this Brown Contract and the Brown Documents, without the prior written consent of Brown, while Brown may at any time assign any of its rights and obligations under this Brown Contract and the Brown Documents without my consent.
18. I agree not to use “Brown,” “Brown & Proud,” “B&P” “BP” and any other trademarks, tradenames, business system or arrangement similar to the Brown Movement or any feature thereof, and other materials used by Brown in connection with the Brown Movement (collectively the “Brown Intellectual Property”), whether or not registered or registrable, without the prior written consent of Brown.
19.  I acknowledge ownership by Brown of the Brown Intellectual Property and shall not directly or indirectly contest or oppose the right and interest of Brown thereto.
20. I shall make no claims of features, functions and capabilities of any Brown Product, and services relating thereto, that are not in the Brown Documents or contained in any official literature or material produced and distributed by Brown.
21.I acknowledge and agree that Brown, by itself or through its affiliated entities, have proprietary rights and interest to the list of Members of Brown (the “Brown Members List”). I will not, in the event I gain access to the Brown Member List, use the same or any Member contact or other detail therein (except those of my Brown Recruits)  to promote any product or services other than the Brown Products. 
22. If any provision of this Brown Contract or the Brown Documents is found to be unenforceable or invalid, the validity of the remaining provisions hereof and thereof shall not be affected and shall remain valid and effective, provided that our essential rights and obligations herein are preserved.
23. This Brown Contract, the Brown Documents and your Membership in the Brown Movement, shall be governed by and interpreted under the laws of the Republic of the Philippines. I agree that venue of any dispute arising out of this Brown Contract, the Brown Documents and/or my Membership in the Brown Movement, including not limited to disputes on breach or applicability of the Brown Contract, shall be in the proper court/s in the City of Makati to the exclusion of all other courts.  In the event of a dispute, the prevailing party shall be entitled to reasonable attorney’s fees, travel and accommodation cost of such party and its witnesses.
24. Brown shall not, regardless of the nature of any demand, claim, suit or action, be liable for any consequential, incidental, special or punitive damages, including lost profits or similar claims. All legal actions shall be filed by the complaining party before the appropriate court within one (1) year from occurrence of the cause or event, or discovery thereof if not immediately discoverable, after which the right of action of the complaining party shall be deemed to have prescribed.
25. I certify that all information provided by me in the registration page for the Brown Movement, including but not limited to my Tax Identification Number, are true, accurate and not misleading.
26. I agree to timely pay, and hereby certify that I will timely pay, any and all taxes applicable to me, including taxes payable on any amount received, including but not limited to rewards, under this Brown Contract and in connection with my Brown Membership, and hereby authorize Brown to withhold such taxes and remit the same to the Philippine Bureau of Internal Revenue (BIR) or appropriate Government agency as are required to be withheld and remitted under Philippine law, rules and regulations.  
27.  In the event of inconsistency between this Brown Contract, the Brown Policies and Procedure and/or the Brown Reward Plan, the Brown Policies and Procedures shall prevail.

Brown Rewards Plan
1)	Brown MLM Plan
The Brown MLM Plan, like other Multi-level Marketing Plans is a compensation plan where participants or members are compensated upon achieving a set level of width and depth in the sale of products. 
However, Brown is unique in that it is set up to the simplest 1x2 achievement level.  That means  a member will only need to recruit 2 members by selling Brown products, in order to earn rewards. Each member is allowed only up to 2 members as immediate downline (level2 of same tree) but will be compensated up to 12 levels where he/she is the 1st level of a “Brown Tree.”

2)	Brown Compensation Plan Rewards.  There are  2 ways to earn from a Brown tree. 
Direct Referral Rewards,  where you directly refer a new Member, and you are registered as the Referrer/Sponsor.
Pairing Rewards, when 2 recruits form a pair under a common upline within your Brown Tree.

3)	The following rewards per Brown Tree may be earned:

a)	Direct Referral Reward: Php500.00
b)	Pairing Reward : Php500.00

4) Safety Net of 12 Levels. 
Earnings stop on the 12th level with a member being the 1st level of your tree. 
Total of 4,095 members per Brown Tree, consisting of 1 member on the 1st level and 2048 members on the 12th level. 
5)	Spill over
Defined as an instance of overflowing or spreading into another area. The spilling over of your recruits to the next available level within your tree as a result of size constraint of the levels. 
Wikipedia- Spillover. The profits of gaining a new member is shared between the entire high-line, which encourages everyone to attempt to recruit new members and make the binary plan larger
In network marketing the term is used to describe a situation where, after signing up the maximum number of recruits permitted on your first level, any person you sign after this maximum 'spills over' to the next level. And if the next level is also filled, to the one following it.
5)	Pay-Out .  There will be a Brown Member’s Dashboard where earnings may be viewed by members. and transferred to EON every 7th and 22nd of the month after the 15days clearing/cut-off.
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
@section("script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/member_register.js"></script>
<script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>

<script>startApp();</script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_register.css">
@endsection