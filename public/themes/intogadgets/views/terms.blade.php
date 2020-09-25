 @section('content')
 @extends('layout')

 <div class="about-container">
 	<div class="about-header container text-left">
 		{!! get_content($shop_theme_info, 'info', 'terms_and_agreement') !!}
 	</div>
 </div>
 @endsection
@section('css')
<link rel="stylesheet" href="resources/assets/frontend/css/about.css">
@endsection