@extends('member.layout')
@section('content')
<div class="append_settings"></div>
@endsection
@section('script')
<script type="text/javascript">
    load_settings();
    function load_settings()
    {
        $('.append_settings').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
        $('.append_settings').load('/member/mlm/plan/binary_promotions/get');
    }
    function submit_done(data)
    {
    	if(data.response_status == 'successd')
    	{
    		load_settings();
    		toastr.success(data.message);
    	}
    	else
    	{
    		load_settings();
    		toastr.error(data.message);
    	}
    }
</script>
@endsection