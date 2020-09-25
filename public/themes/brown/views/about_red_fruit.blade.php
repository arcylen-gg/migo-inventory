@extends("layout")
@section("content")
<div class="content">
	<!-- CONTENT -->
	<div class="mid-content">
		<div class="container">
			<!-- PER DETAIL -->
			<div class="detail-container row clearfix">
				<div class="col-md-6">
					<div class="description-container">
						<div class="title-container">About Red Fruit
						</div>
						<div class="description-content">
							<p>
								When the harvest of red fruit, the people of Papua usually red fruit like people on the Java island that make a coconut oil. Red Fruit Oil (red fruit extract often called the red fruit oil) are stored in bamboo and can be used for one year. Reserves of this oil used for cooking food, like a cooking oil. Red fruit oil is used to substitute cooking oil because oil prices in the hinterland is relatively expensive.<br><br>

								UNtil now, red fruit still used by Papua's people. Most of the population that consume red fruit, wether in pasta for daily food and also the oil. They are rarely affected by disease, string body, and have prime stamina. This question invites newcomers community, so that not a few of them started trying to take advantage of red fruit, especially the oil.<br><br>

								Based on the indiviuals research although institute research about the contents of red fruit, newcomers communiyu join to exploit this fruit from the hinterland. Until now almost all elements of society, from people which use koteka until government officials, active to process the red fruit. Therefore not surprising if the red fruit called as red gold from the jungle of Papua. Processed result of red fruit oil as sold as drugs that help to cure many different types of diseases, such as HIV/AIDS, cancer/tumor, hemorrhoid, diabetes mellitus, uric acid, rheumatism, coronary heart, lungs, asthma, heart and kidney trouble, high blood pressure, eczema, and herpes. 
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="img-container">
						<img src="/themes/{{ $shop_theme }}/img/buah-merah.png">
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/about_red_fruit.css">
@endsection

@section("js")
<script type="text/javascript">
$(document).ready(function()
{
	/*scroll up*/
	$(window).scroll(function () {
        if ($(this).scrollTop() > 700) {
            $('.scroll-up').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
        }
    });

    $('.scroll-up').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 700);
        return false;
    });
});
</script>
@endsection