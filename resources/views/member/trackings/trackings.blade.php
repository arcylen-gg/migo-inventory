@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Trackings </span>
                <small>
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right" size="md">Add New</a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="panel-body">
        <div class="as-track-button" data-size="normal" data-domain="bryanxkier.aftership.com"></div>
    </div>
</div>
<div id="as-root"></div>
@endsection
@section('script')
<script>

var trackings = new trackings();

function trackings()
{
    init();
    function init()
    {
        document_ready();
    }

    function document_ready()
    {

    }
}

(function(e,t,n){var r,i=e.getElementsByTagName(t)[0];if(e.getElementById(n))return;
r=e.createElement(t);r.id=n;r.src="//button.aftership.com/all.js";i.parentNode.insertBefore(r,i)})(document,"script","aftership-jssdk")

</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection
