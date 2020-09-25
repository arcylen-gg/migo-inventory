<h2 class="text-center">{{$shop_name}}</h2>
<h4 class="text-center"><b>{{$head_title}}</b></h4>
<h4 class="text-center"><b>{{$head_discription or ''}}</b></h4>
<h4 class="text-center">{{isset($from) && $from != '1000-01-01' ? date_format(date_create($from),"F d, Y ") : date('F d, Y')}}</h4>
