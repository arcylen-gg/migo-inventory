@extends("mlm.register.layout")
@section("content")

	<div class="container-fluid">
		<div class="register">
			<div class="title"><b><h1>YOU ALREADY HAVE AN ACCOUNT</h1></b><h5>What do you want to do?</h5></div>
			<div class="form-container">
				<div class="row clearfix">
					<br>
					<form class="register-submit" method="post" action="/member/register/logged_in">
					{!! csrf_field() !!}
					<input type="hidden" name="used_button" value="create" class="used_button">
					<div class="col-md-offset-3 col-md-6">
						
						<div class="col-md-12">	
							<div class="form-group">
								<label> Refferal Code {{$sponsor_r == 1 ? '' : '(Optional)'}}</label>
								<input type="text" class="form-control input-lg" name="sponsor" value="{{$sponsor}}" 
								{{$sponsor_r == 1 ? 'required' : ''}} 
								>
							</div>
						</div>	

					
						<div class="col-md-12">
							<div class="col-md-6">
								<div class="form-group">
									<label><input type="radio" name="account_use"  value="0" checked> Use Old Account</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><input type="radio" name="account_use"  value="1"> Create New Account</label>
								</div>
							</div>
						</div>
						<div class="col-md-12 text-center">
							<div class="button-holder">
								<button class="modal-button btn btn-green btn-lg"  value="create" >Submit</button>
							</div>
						</div>
					</div>
					</form>	
				</div>
			</div>		
		</div>
	</div>	
@endsection

@section("css")
<!-- <link rel="stylesheet" href="themes/ecommerce-1/css/checkout_payment.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="assets/mlm/css/register-payment.css"> -->
<link rel="stylesheet" type="text/css" href="assets/mlm/css/register.css">
<style type="text/css">
	.modal-button
	  {
	    color: #21cc21;
	      border-color: #21cc21;
	      background-color: transparent;
	  }
</style>
@endsection

@section('script')
<script type="text/javascript">
	$(document).on("submit", ".register-submit", function(e)
    {
        var data = $(e.currentTarget).serialize();
        var link = $(e.currentTarget).attr("action");
        $('#load').removeClass('hide');
        submit_form_register(link, data);
        e.preventDefault();
        
    })
	function submit_form_register(link, data)
    {
        
        $.ajax({
            url:link,
            dataType:"json",
            data:data,
            type:"post",
            success: function(data)
            {
            	$('#load').addClass('hide');
            	if(data.status == 'warning')
            	{
            		var message = data.message;
            		$.each( message, function( index, value ){
					    toastr.warning(value);
					});
            	}
            	else if(data.status == 'success')
            	{
						window.location = data.link;            		
            	}
            },
            error: function()
            {
                $('#load').addClass('hide');
            }
        })
    }
</script>
@endsection