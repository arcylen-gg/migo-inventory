<div data-page="report" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="index.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Enter Sponsor</div>
            <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
        </div>
    </div>
    <div class="page-content">
        <div class="enter-sponsor-holder">
            <form method="post" class="submit-verify-sponsor">
                <div class="labels">{!! $message !!}</b></div>
                
                @if($lock_sponsor)
                    <input disabled required="required" class="input-verify-sponsor text-center" name="verify_sponsor" type="text" value="{{ $lock_sponsor }}">
                @else
                    <input required="required" class="input-verify-sponsor text-center" name="verify_sponsor" type="text" placeholder="">
                @endif
                
                <div class="output-container">
                </div>
                
                <div class="btn-container">
                    <button id="btn-verify" class="btn-verify btn-verify-sponsor"><i class="fa fa-check"></i> VERIFY SPONSOR</button>
                </div>
            </form>
        </div>
    </div>
</div>