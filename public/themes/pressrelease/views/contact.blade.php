@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
<div class="main-wrapper">
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-8">
                <div class="title-container">Get In Touch With Us</div>
                <form action="Post"> 
                    @if (session('message_concern'))
                        <div class="alert alert-success">
                            {{ session('message_concern') }}
                        </div>
                    @endif
                 <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="First Name*" id="first_name" name="first_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Last Name*" id="contactus_last_name" name="contactus_last_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                                <input type="phone" class="form-control" placeholder="Phone*" id="contactus_phone_number" name="contactus_phone_number" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email Address*" id="contactus_email" name="contactus_email" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text area" class="form-control" placeholder="Subject*" id="contactus_subject" name="contactus_subject" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea type="text" class="form-control text-message" placeholder="Message*" id="contactus_message" name="contactus_message" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="button-container">
                           <button type="submit" formaction="/contactus/send">Send</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <div class="col-md-4">
            	<div class="background-border">
            		<div class="background-container">
		        		<div class="title-info-container">Email Address</div>
		        		<div class="contact-info"><i class="fa fa-envelope" aria-hidden="true"></i><span>marketing@press-iq.com</span></div>
		        		{{-- <div class="title-info-container">Contact Number</div>
		        		<div class="contact-info"><i class="fa fa-phone" aria-hidden="true"></i><span>+4736 - 9806 - 890</span></div> --}}
	        		</div>
        		</div>
            </div>
        </div>
    </div>
</div>

    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/contact.css">
@endsection

@section("script")

<script type="text/javascript">

</script>

@endsection