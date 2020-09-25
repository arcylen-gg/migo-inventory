<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/member/js/test/test.js"></script>

<input class="token" type="texts" value="{{ csrf_token() }}" name="">
<button class="load-table" name="">
	<span class="table-loader"><i class="fa fa-table"></i></span> Load Table
</button>


<select class="filter-by-shop">
	<option value="0">All Shop</option>
	@foreach($_shop as $shop)
	<option value="{{ $shop->shop_id }}">{{ $shop->shop_key }}</option>
	@endforeach
</select>

<div class="table-container"></div>