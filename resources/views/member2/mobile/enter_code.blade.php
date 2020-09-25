<div data-page="report" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="index.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Enter Placement</div>
            <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
        </div>
    </div>
    <div class="page-content">
        <div class="code-holder">
            <div class="message-return-code-verify"></div>
            <form class="code-verification-form">
                <div>
                    <div class="labeld">Pin Code</div>
                    <input class="input input-pin text-center" name="pin" type="text" value="{{$mlm_pin or ''}}">
                </div>
                <div>
                    <div class="labeld">Activation</div>
                    <input class="input input-activation text-center" name="activation" type="text" value="{{$mlm_activation or ''}}">
                </div>
                <div class="btn-container">
                    <button id="btn-proceed-2" class="btn-proceed-2"><i class="fa fa-angle-double-right"></i> Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>