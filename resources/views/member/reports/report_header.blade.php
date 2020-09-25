<h2 class="text-center">{{$shop_name}}</h2>
<h4 class="text-center"><b>{{$head_title}}</b></h4>
<h4 class="text-center"><b>{{$head_discription or ''}}</b></h4>
<h4 class="text-center">{{isset($from) && $from != '1000-01-01' ? ($from == $to ? date('F d, Y',strtotime($from)) : date('F d, Y',strtotime($from))." - ".date('F d, Y',strtotime($to))): 'All Dates'}}</h4>
