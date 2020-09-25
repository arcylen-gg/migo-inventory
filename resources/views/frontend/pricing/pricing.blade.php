@extends("frontend.layout")
@section("content")
<div class="blue-bar" id="scroll-to">
    Buy Now!
</div>
<div class="container clearfix">
    <div class="plan">
        <div class="title">First, choose a plan. Try it free for 30 days or subscribe now to enjoy discounts.</div>
        <div class="row clearfix">
            <div class="col-md-4">
                <div class="pricing-card">
                    <div class="card-header">
                        <h4>Digima House Online</h4>
                        <h3>Simple Start</h3>
                    </div>
                    <div class="usual-price">usual <del style="font-size: 14px;">PHP. 15.00</del></div>
                    <div class="price-holder">
                        <span class="price">
                            <span class="ct">PHP.</span>
                            <span class="aw">12</span>
                            <span class="as">.</span>
                            <span class="ac">00</span>
                        </span>
                        <span class="per">/mth</span>
                    </div>
                    <button class="btn blue" type="button" onClick="location.href='/register'">Buy Now & Save 20%</button>
                    <div class="link">
                        <a href="/register">Try It Free</a>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Start your business:</div>
                        <ul>
                            <li>Track sales, expenses and profits</li>
                            <li>Create & send unlimited invoices</li>
                            <li>Track and manage your sales tax</li>
                            <li>Works on PC, Mac, and mobile</li>
                            <li>For one user, plus your accoutant</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing-card">
                    <div class="card-header">
                        <h4>Digima House Online</h4>
                        <h3>Essentials</h3>
                    </div>
                    <div class="usual-price">usual <del style="font-size: 14px;">PHP. 23.00</del></div>
                    <div class="price-holder">
                        <span class="price">
                            <span class="ct">PHP.</span>
                            <span class="aw">16</span>
                            <span class="as">.</span>
                            <span class="ac">10</span>
                        </span>
                        <span class="per">/mth</span>
                    </div>
                    <button class="btn blue" type="button" onClick="location.href='/register'">Buy Now & Save 20%</button>
                    <div class="link">
                        <a href="/register">Try It Free</a>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Start your business:</div>
                        <ul>
                            <li>Get all Simple Start features</li>
                            <li>Manage and pay bills</li>
                            <li>Transact in multiple currencies</li>
                            <li>Generate sales quotes</li>
                            <li>For three users, plus your accountant</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pricing-card active">
                    <div class="card-header">
                        <h4>Digima House Online</h4>
                        <h3>Plus</h3>
                    </div>
                    <div class="usual-price">usual <del style="font-size: 14px;">PHP. 31.00</del></div>
                    <div class="price-holder">
                        <span class="price">
                            <span class="ct">PHP.</span>
                            <span class="aw">31</span>
                            <span class="as">.</span>
                            <span class="ac">00</span>
                        </span>
                        <span class="per">/mth</span>
                    </div>
                    <button class="btn blue" type="button" onClick="location.href='/register'">Buy Now & Save 20%</button>
                    <div class="link">
                        <a href="/register">Try It Free</a>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Start your business:</div>
                        <ul>
                            <li>Get all Essentials features</li>
                            <li>Track inventory</li>
                            <li>Create purchase orders</li>
                            <li>Track project or job profitability</li>
                            <li>For five users, plus your accountant</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="available">
        <div class="row clearfix">
            <div class="title col-md-5 col-md-offset-1">Available with every plan:</div>
        </div>
        <div class="row clearfix">
            <div class="col-md-5 col-md-offset-1">
                <ul>
                    <li>No downloads, no contract, cancel anytime</li>
                    <li>Free unlimited support</li>
                    <li>Fre updates & automated backups</li>
                </ul>
            </div>
            <div class="col-md-5">
                <ul>
                    <li>Upgrade to a higher plan anytime</li>
                    <li>Free Mobile app</li>
                    <li>Bank level security (256 bit SSL encryption)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@section("css")
<link rel="stylesheet" href="/assets/front/css/pricing.css" type="text/css" />
@endsection