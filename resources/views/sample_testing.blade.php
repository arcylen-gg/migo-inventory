<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<h1>{{ $id }}</h1>

@if($id == 1)
<h1>6</h1>
@elseif($id == 2)
<h1>7</h1>
@else
<h1>9</h1>
@endif


@foreach($arrays as $arr)
<p>{{ $arr }}</p>
@endforeach

</body>
</html>