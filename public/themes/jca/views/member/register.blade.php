@extends("layout")
@section("content")
@include("member2.include_register")
<!-- Modal -->
<div class="modal fade modal-agreement" id="modal_agreement" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">Accept JCA Privacy Policy</div>
            <div class="modal-body">
                <div class="contract">
                	<div class="header">Privacy Policy</div>
                	<div class="introduction">
	            		<div class="title">Introduction</div>
	                	<div class="para-container">
	                		<p>This privacy policy sets out rules for the collection, use and disclosure of personal information and how JCA Wellness International Corp. uses and protects the information that you.</p>
	                		<p>JCA Wellness International Corp. is committed to guaranteeing that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using the website, then you can be assured that it will only be used in accordance with this privacy statement. </p>
	                		<p>JCA Wellness International Corp. may change and update this policy from time to time and any information we collect is not used and shared with this privacy statement.</p>
	                	</div>
                	</div>
					
					<div class="personal-info">
						<div class="title">Personal Information</div>
						<div class="para-container">
							<p>Personal Information means any information about an identifiable individual. It includes, without limitation, information relating to identify, nationality, age, gender, address, telephone number, e-mail address, date of birth, and other information relating to our transaction with you as well as certain personal opinions or views of an individual. Only information which is required to make a determination of an individual’s eligibility will be collected. You may choose to restrict the collection or use of your personal information by indicating that you do not want the information for direct marketing purposes. We require this information to understand your needs and provide you with a better service and in particular for the following reasons:</p>
							<ol>
								<li>Record-keeping;</li>
								<li>Send instructive and promotional email about new products or other information which we think you may find interesting. </li>
								<li>For market research purposes</li>
								<li>Or we may use the information to customize the website according to your requirements</li>
							</ol>
						</div>
					</div>

					<div class="share-protect">
						<div class="title">Sharing and Protecting your Information</div>
						<div class="para-container">
							<p>JCA Wellness International Corp. will not sell, distribute or lease your personal information to other parties unless we have your permission or are required by law to do so.</p>
							<p>To prevent unauthorized access, maintain data accuracy, and ensure information usage, we have put into place appropriate physical, electronic and managerial procedures to safeguard and secure these information we collected online. We will also take reasonable steps to verify your identify before granting access or make corrections.</p>
						</div>
					</div>
                	
					<div class="contact-us">
						<div class="title">Contact Us</div>
						<div class="para-container">
							<p>Should you have any questions or concerns about this Privacy Policy, on or use of your information or would like to correct factual errors in your personal information, or in the unlikely event any of the personal information you provided is misused, you may redress these issue by reaching us directly at telephone number (02) 631-6997 or at mobile number 0926-6494134 (globe) 0946-5619714 (smart).</p>
						</div>
					</div>

					<div class="disclaimer">
						<div class="title">Disclaimer</div>
						<div class="para-container">
							<p>The contents of this website, jcawellnessintcorp.digimahouse.com are for informational purposes only and do not render medical or psychological advice, opinion, diagnosis or treatment. The products as shown in this website are not intended to diagnose, treat, cure or prevent any disease or medical condition.</p>
						</div>
					</div>

					<div class="dealers-policy">
						<div class="title">Dealers Policy:</div>
						<div class="para-container">
							<p>As a new member of JCA Wellness International Corp., I understand and agree to the following: THAT</p>
							<ol>
								<li>I attest that I personally participated at JCA International Corp.</li>
								<li>As a JCA International Corp Member, I hereby understood that there is no required minimum inventory that I need to maintain in order to sustain my status as a Member.</li>
								<li>I shall be eligible to bonuses and privileges that may be granted by the company relative to my performance. Relevant to my activity, I further understand that bonuses, rebates and/or commissions are in accordance with the compensation scheme established in the JCA International Corp Marketing Plan, provided, that I have achieved such sales performance in good faith, and that I have not violated any of the provisions of the Distributorship Policy, </li>
								<li>This Membership Contract limits a no employee – employer relationship between me and JCA International Corp; neither that I may claim to be a legal representative of JCA International Corp, nor bind JCA International Corp in any agreements other than those agreed herein;</li>
								<li>I understand that I should secure an express written approval from the Organization of JCA International Corp or any of its authorized representative prior to making any form of advertisement in mainstream media, social networking sites, internet, and online media, including, but not limited to audio, visual and printed materials, other than the Company’s existing advertisement and marketing materials and postings on its official website and social media pages;</li>
								<li>I am aware that making any misrepresentations, revisions, modifications, or alterations of the Company’s trademark, brand, logos, marks, marketing plan, products, advertisement, marketing materials, and other company provided marketing tools, is strictly prohibited. Otherwise, it shall be deemed violation of the Company’s rules, regulations and policies;</li>
								<li>I shall diligently settle to the designated government agency/local government unit all due taxes from the taxable bonuses I have earned from JCA International Corp.</li>
								<li>I am fully aware that the JCA Wellness International Corp reserves the right to modify, revise, and update its existing policies and business plan for the best interest of the company and its Distributors with 30 days prior notice;</li>
								<li>
									<p>I conform to conduct all my activities in accordance with the existing laws of the Republic of the Philippines and release JCA International Corp from any liability arising from my own personal actions;</p>
									<p>I hereby certify that the above information are true and correct to the best of my knowledge. In case of violation of any of the terms and conditions herein agreed, I hereby agree and allow JCA Wellness International Corp to revoke, deactivate, and/or suspend my privileges as Member without prejudice to any charges, criminal and/or civil, that JCA Wellness International Corp. may charge against me, without limitation to any false information I have provided herein.</p>
									<p>I hereby certify that the above information are true and correct to the best of my knowledge. In case of violation of any of the terms and conditions herein agreed, I hereby agree and allow JCA Wellness International Corp to revoke, deactivate, and/or suspend my privileges as Member without prejudice to any charges, criminal and/or civil, that JCA Wellness International Corp. may charge against me, without limitation to any false information I have provided herein.</p>
								</li>
							</ol>
						</div>
					</div>
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