@extends('frontend.layout')
@section('content')
<!--<div><a href="/login">Click here to proceed to login page.</a></div>-->
<div class="intro">
    <div class="container">
        <div class="col-md-12 col-sm-12">
            <div class="intro-testimony">
                <h1 style="line-height: 1.1; letter-spacing: 0px; color: #fff;">Get Paid 2x Faster</h1>
                <h2 style="font-size: 20px; margin-top:11px; margin-bottom: 10px; color: #fff;">Send invoices and quotations on the go.</h2>
                <div class="btn-container">
                    <button class="btn white" onClick="location.href='/register'">FREE TRIAL</button>
                    <button class="btn green">BUW NOW & SAVE 40%</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-sitemap" id="scroll-to">
    <ul>
        <li><a href="javascript:">Plans & Pricing</a></li>
        <li><a href="javascript:">Why QBO</a></li>
        <li><a href="javascript:">QB Desktop User?</a></li>
        <li><a href="javascript:">Add-on Apps</a></li>
    </ul>
</div>
<div class="home-content">
    <div class="title">Get paid faster – create invoices instantly</div>
    <div class="sub">Create quotes and invoices wherever you are, with a few taps.</div>
</div>
<div class="home-content">
    <div class="title">Save time – capture receipts on the go</div>
    <div class="sub">Take photos of your receipts and create expenses wherever you are – always be ready for tax time.</div>
</div>
<div class="home-content gray">
    <div class="title">What our customers have to say about Digima House Online</div>
    <!--<div class="sub">Create quotes and invoices wherever you are, with a few taps.</div>-->
    <div class="container">
        <div class="testimonial">
            <div class="row clearfix">
                <div class="col-sm-4">
                    <img class="img-responsive" src="/assets/front/img/placeholder.png">
                    <div class="name">Person Name</div>
                    <div class="subname">Empreus International</div>
                    <div class="say">"I run my administrative process virtually without an office."</div>
                </div>
                <div class="col-sm-4">
                    <img class="img-responsive" src="/assets/front/img/placeholder.png">
                    <div class="name">Person Name</div>
                    <div class="subname">Empreus International</div>
                    <div class="say">"I run my administrative process virtually without an office."</div>
                </div>
                <div class="col-sm-4">
                    <img class="img-responsive" src="/assets/front/img/placeholder.png">
                    <div class="name">Person Name</div>
                    <div class="subname">Empreus International</div>
                    <div class="say">"I run my administrative process virtually without an office."</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="home-content">
    <div class="title">See why SMEs in Philippines love Digima House Online</div>
    <!--<div class="sub">Take photos of your receipts and create expenses wherever you are – always be ready for tax time.</div>-->
    <div class="container why">
        <div class="col-md-6 text-center">
            <img class="img-responsive" src="/assets/front/img/icon/1.png">
            <div class="why-title">No installation required</div>
            <div class="why-sub">Simply log in to Digima House through your web browser or the Digima House mobile app.</div>
        </div>
        <div class="col-md-6 text-center">
            <img class="img-responsive" src="/assets/front/img/icon/2.png">
            <div class="why-title">No cluncy company files</div>
            <div class="why-sub">You and your accountant can collaborate remotely on your data at the same time, and you’ll see your data updated in real-time.</div>
        </div>
        <div class="col-md-6 text-center">
            <img class="img-responsive" src="/assets/front/img/icon/3.png">
            <div class="why-title">No more back-ups</div>
            <div class="why-sub">Never worry about saving your file to a USB drive. Digima House Online is backed up with bank-level security.</div>
        </div>
        <div class="col-md-6 text-center">
            <img class="img-responsive" src="/assets/front/img/icon/4.png">
            <div class="why-title">Less paper to track</div>
            <div class="why-sub">Take photos of receipts and use the mobile app to attach them to transactions in Digima House.</div>
        </div>
        <div class="col-md-6 text-center">
            <img class="img-responsive" src="/assets/front/img/icon/5.png">
            <div class="why-title">Less on your to-do list</div>
            <div class="why-sub">Schedule reports to run automatically, so you don't have to worry about them.</div>
        </div>
        <div class="col-md-6 text-center">
            <img class="img-responsive" src="/assets/front/img/icon/6.png">
            <div class="why-title">Less stress</div>
            <div class="why-sub">Our experts can help you get started, answer questions, and more. 7:30am-5:00pm (+8GMT), Mon-Fri.</div>
        </div>
    </div>
</div>
<div class="container">
    <div class="last clearfix">
        <div class="quote">Get started with Digima House now</div>
        <div class="col-md-4 col-md-offset-2">
            <button class="btn shiro">START FREE 30-DAY TRIAL</button>
            <div class="note">No credit card required.</div>
        </div>
        <div class="col-md-4">
            <button class="btn kuro">BUY NOW & SAVE UP TO 80%</button>
            <div class="note">No obligation, cancel anytimee</div>
        </div>
    </div>
</div>
@endsection
@section("css")
<link rel="stylesheet" href="/assets/front/css/home.css" type="text/css" />
@endsection