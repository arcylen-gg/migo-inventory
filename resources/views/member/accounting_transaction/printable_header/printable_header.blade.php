@extends('member.layout')
@section('content')
 <div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-check"></i>
            <h1>
            <span class="page-title">{{$page or ''}}</span>
            <small>
            	You can customize here your header in all printable transaction
            </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
	  <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#headersettings"><i class="fa fa-h-square"></i> Header Settings</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#papersize"><i class="fa fa-address-book"></i> Paper Size</a></li>
    </ul>
    <div class="data-container" >
        <div class="tab-content">
        	<div id="headersettings" class="tab-pane fade in active">
	            <div class="row">
	                <div class="col-md-12 header-setting-blade" style="padding: 30px;">
	                	@include("member.accounting_transaction.printable_header.header_setting")
	                </div>
	            </div>
           	</div>
        	<div id="papersize" class="tab-pane fade in">
	            <div class="row">
	                <div class="col-md-12 paper-size-setting-blade" style="padding: 30px;">
	                	@include("member.accounting_transaction.printable_header.paper_size_setting")
	                </div>
	            </div>
           	</div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	
	$("body").on("change",".papersize-select", function()
	{
		$(".actual-paper").height(parseFloat($(this).find("option:selected").attr("size-h")) * 37.795276);
		$(".actual-paper").width(parseFloat($(this).find("option:selected").attr("size-w")) * 37.795276);
	});
	function success_settings(data)
	{
		if(data.response_status == 'success_update')
		{
			toastr.success(data.message);
			location.reload();
		}
	}
	$("body").on("click",".check-select", function()
	{
		$trans = $(this).attr("row-name");
		if($(this).prop("checked"))
		{
			$(".input-select."+$trans).val(1);
			$(".input-size."+$trans).removeClass("hidden");
			$(".select-size."+$trans).addClass("hidden");
		}
		else
		{
			$(".input-select."+$trans).val(0);
			$(".input-size."+$trans).addClass("hidden");
			$(".select-size."+$trans).removeClass("hidden");		}
	})
</script>
<script type="text/javascript" src="/assets/member/js/tinymce.min.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>
@endsection