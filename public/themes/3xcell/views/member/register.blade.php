@extends("layout")
@section("content")

@include('member2.include_register')
<!-- Modal -->
<div class="modal fade modal-agreement" id="modal_agreement" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">Accept 3xcell Terms and Conditions</div>
            <div class="modal-body">
                <div class="contract">
                    <div class="holder">
                        <div class="title-holder">TERMS AND CONDITIONS</div>
                        <div class="para-holder">
                            By placing an order with 3xcell E Sales & Marketing Inc... Through the 3xcell E website, you are accepting these Terms & Conditions. Please read these Terms & Conditions before placing an order.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">IN THESE TERMS & CONDITIONS</div>
                        <div class="para-holder">
                            “We” and “us” means 3xcell E Sales & Marketing Inc.
                            “You” means the person placing an order.
                            “The contract is agreed” – by completing and submitting an electronic order form, you are making an offer to purchase products which, if accepted by us, will result in a binding contract. An acceptance email from us confirms that the contract is formed.
                            By using the website to buy products online, you represent that you are at least 18 years old.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">REGISTRATION</div>
                        <div class="para-holder">
                            Step 1: Go to our website <a target="_blank" href="www.3xcell.com">www.3xcell.com</a><br>
                            Step 2: Click <strong>Join Us Today</strong><br>
                            Step 3: Fill out correctly all the information. Read and Accept the Terms & Conditions.<br>
                            Step 4: Click <strong>Sign Up</strong><br>
                            Step 5: Upgrade your account. <strong>Enter your Membership Code</strong>
                            <br><br>
                            Congratulations! You’re now a 3xcell-E Distributor. You can now view and personalize your member’s dashboard. Thank you.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">ORDERING</div>
                        <div class="para-holder">
                            By completing and submitting an online order form, you are making an offer to purchase goods which, if accepted by us, will result in a binding contract. Please note that products will not be sent until we have authorization from your payment. We will notify you that your order is being processed by sending an order confirmation via email; however, we do not formally accept your offer until your order has passed our internal validation procedures for verifying the bona fide of each order placed, for the purpose of preventing payment fraud. 
                            <br><br>
                            <i>After you have placed your order:</i>
                            <br><br>
                            You will receive an email to acknowledge your order. It will confirm which products you have ordered. This email is not an order acceptance from us.
                            When an order has been placed, you will be redirected to a ‘thank you’ page which will require you to verify your name and email address. Once that information has been verified, you will be redirected to a download page which will allow you to download the purchased content. You will also receive an email containing the link to the download page.
                            <br><br>
                            We do not have to accept your order, and for example, we will not accept your order if:
                            <ul>
                                <li>We do not have the products in stock.</li>
                                <li>Your payment is not authorized.</li>
                                <li>There is an error on our website regarding the price or other details of the products.</li>
                                <li>Product delivered did not match on the description on website.</li>
                                <li>The company shipped with the wrong product.</li>
                                <li>You have cancelled it in accordance with the instructions below.</li>
                            </ul>
                            3xcell-E Sales and Marketing Inc. reserve the right to refuse, terminate or cancel orders in their sole discretion.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">CANCELLATION OF ORDERS</div>
                        <div class="para-holder">
                            If you change your mind after placing an order, you can cancel it within 24 hours’ time before your payment. Once order is already paid and approved by us, <strong>No Cancellation</strong> is allowed therefore item is considered sold. If no payment made within 24 hours orders will be automatically cancelled.<br>Please see the Contact us page for our telephone number. You will need to give us your name and address details, as well as your order number to cancel your order.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">RETURN</div>
                        <div class="para-holder">
                            Each product sold through E Commerce website of 3xcell E is guaranteed. We can only accept return if it is stated under the circumstances of our Return Policy. To know more the processes of return, please read our return policy section. You may contact us via email sales@3xcell.com or telephone number (02) 518-8637.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">PRICING AND PAYMENT</div>
                        <div class="para-holder">
                            Prices throughout the website are quoted in Philippine peso, and payment can only be accepted in Philippine peso. Packing and delivery costs, if any, will be added to the total price of your purchase.<br>While we make every effort to ensure that the products shown on our website are currently available at the price shown, we cannot guarantee that this will always be the case. If products you have ordered are unavailable, you will be notified as soon as possible.<br>The price you pay is the price of the products shown at the time you place your order, even if the price of the product has since changed.<br>Your payment is required at the time the order is placed. By submitting an online order with 3xcell E Sales & Marketing Inc... Through the 3xcell E website, you expressly agree not to request a ‘charge back’ of any fees or payments for said orders, and that no dispute with 3xcell E Sales & Marketing will be raised with or adjudicated by the any bank.
                        </div>
                    </div>
                    <div class="step"><i>Step by Step Instructions</i></div>
                    <div class="holder">
                        <div class="title-holder">STEP 1: PAYMENT</div>
                        <div class="para-holder">
                            1. Go to the nearest/bank or remittance center that is listed on the 3xcell account list.<br>
                            2. Deposit or remit on the given details in the 3xcell account list.<br>
                            3. Make sure that a proof of payment is provided once you deposit or remit. Attach pictures for proof of payment and fill up completely the other details such as Date and time of payment, Bank Reference Number, Senders Name and Amount deposited.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">STEP 2: CONFIRMATION</div>
                        <div class="para-holder">
                            1. Visit this URL, which you can easily reach using your e-mail address.<br>
                            2. On this page, you can upload a copy of your proof of payment. You can upload a scanned copy or a copy captured by your camera.<br>
                            3. We will send a confirmation email to you once processed. If you do not receive one within 48 hours, you may call or contact us directly using this e-mail address. 
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder"><u>GENERAL RULES</u></div>
                        <div class="para-holder">
                            <ul>
                                <li>Pay the exact amount indicated above. Excess portion of your payment is forfeited. Payment less than the amount due will not be processed.</li>
                                <li>If you are paying multiple ORDERS Reference Numbers, pay separately for each reference number. Do not lump them into a single transaction.</li>
                                <li>If you made a short payment by mistake, to not try to correct it by making another bills payment with the same reference number.</li>
                                <li>Contact us immediately if you made a mistake in your payment.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">DELIVERY INFORMATION</div>
                        <div class="para-holder">
                            Please see individual product sales pages for information about delivery and shipping charges, where applicable. 
                            For products that require shipping, we will email you as soon as your order has been shipped, and will advise of the shipping method (if any) at that time.<br><br>
                            3xcell-E Sales and Marketing Inc. delivery dates are estimates only and are not liable for delays in delivery or for failure to perform due to causes beyond the reasonable control, nor shall the carrier be deemed an agent of 3xcell-E Sales and Marketing Inc. A delayed delivery of any part of an order does not entitle the buyer to cancel other deliveries.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">CHANGES OF TERMS AND CONDITIONS</div>
                        <div class="para-holder">
                            We reserve the right to change these Terms & Conditions for buying products online from time to time. If this happens, we will notify you by posting the new Terms & Conditions for buying products online on the website. If you do not wish to be governed by the revised Terms & Conditions for buying products online, you must not place any further orders.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">GOVERNING LAW AND JURISDICTION</div>
                        <div class="para-holder">
                            These Terms & Conditions for buying products online and the relationship between the parties shall be governed by and construed in accordance with the laws of the Republic of the Philippines without reference to its conflicts of law provisions. Any and all claims, causes of action, or disputes relating or pursuant to, arising out of or in connection with these Terms of Use shall be brought before the appropriate courts of the Republic of the Philippines to the exclusion of all other courts and venues. You agree to submit to the personal jurisdiction of these courts and hereby waive any and all objections to the exercise of jurisdiction over the parties by such courts and to venue in such courts on any ground, including without limitation on the ground of inconvenient forum. However, nothing herein shall preclude 3XCELL E SALES & MARKETING INC. from seeking injunctive relief (or any other provisional remedy) from any court having jurisdiction over you or taking proceedings against you in any other court of competent jurisdiction.
                            If any part of these Terms & Conditions for buying products online is found to be invalid by law, the rest of them remain valid and enforceable.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">LIMITATION OF LIABILITY</div>
                        <div class="para-holder">
                            You Expressly Understand and agree that 3xcell E Sales & Marketing Inc and its Affiliates, Officers, employees, and agents will have no liability whatsoever to you, to any party claiming by, through or against your or to any third party for any retributive , indirect, incidental, special, consequential or exemplary damages, including without limitation, costs , charges or damages constituting loss of profits, use and data, whether if that may arise out of or in connection with (1) your use or inability to use this website or access the contents, (2) the cost of procurement of substitute goods or services, (3) Unauthorized access to or alteration of your transmission or data, or (4) any other matters relating to the website and the contents.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">3XCELL RIGHTS</div>
                        <div class="para-holder">
                            YOU ACKNOWLEDGE AND AGREE THAT 3XCELL-E SALES & MARKETING INC. HAS THE RIGHT TO DISCLOSE YOUR PERSONAL INFORMATION TO ANY LEGAL, REGULATORY, LAW ENFORCEMENT, TAX,GOVERNMENTAL,  OR OTHER AUTHORITIES, IF 3XCELL–E SALES & MARKETING INC. HAS REASONABLE GROUNDS TO BELIEVE THAT DISCLOSURE OF YOUR PERSONAL INFORMATION IS NECESSARY FOR THE PURPOSE OF MEETING ANY OBLIGATIONS, REQUIREMENTS OR ARRANGEMENTS, WHETHER VOLUNTARY OR MANDATORY, AS A RESULT OF COOPERATING WITH AN ORDER, AN INVESTIGATION AND/OR A REQUEST OF ANY NATURE BY SUCH PARTIES. TO THE EXTENT PERMISSIBLE BY APPLICABLE LAW, YOU AGREE NOT TO TAKE ANY ACTION AND/OR WAIVE YOUR RIGHTS TO TAKE ANY ACTION AGAINST 3XCELL-E SALES & MARKETING INC FOR THE DISCLOSURE OF YOUR PERSONAL INFORMATION IN THESE CIRCUMSTANCES.
                        </div>
                    </div>
                    <div class="holder">
                        <div class="title-holder">CONTACT DETAILS</div>
                        <div class="para-holder">
                            For more questions and concerns you may contact us via telephone number <strong>(02) 518-8637</strong> Metro Manila Office or mailing as at Unit 202 Vicars Bldg.  # 31 Visayas Avenue Brgy. Vasra 1 Quezon City, Philippines or send your concerns via email sales@3xcell.com 
                            <br><br>
                            Thank you,<br>
                            3xcell E Sales & Marketing
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
@section("js")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/member_register.js"></script>
<script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>

<script>startApp();</script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_register.css">
@endsection