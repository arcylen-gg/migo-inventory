 <td>
    <a href="#" data-content="{{$customer[0]['customer_id']}}" class="a-customer" data-toggle="modal" data-target="#layoutModal">{{$customer[0]['name']}}</a>
</td>
<td class="text-center">
    {{$customer[0]['location']}}
</td>
<td class="text-center">
    {{$customer[0]['totalorder']}}
</td>
<td class="text-center">
    <a href="#">{{$customer[0]['lastorder'] == ''?'':'#'.$customer[0]['lastorder']}}</a>
</td>
<td class="text-right">
    <span class="pull-left">PHP</span>{{number_format($customer[0]['totalSpent'],2)}}
</td>