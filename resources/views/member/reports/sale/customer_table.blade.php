@if($item)
    @foreach($item as $key => $cust)
    <tr>
        <td><a href="#">{{$cust['info']->first_name." ".$cust['info']->last_name}}</a></td>
        <td><a href="#">{{$cust['info']->email}}</a></td>
        <td class="text-center">{{$cust['quantity']}}</td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('',$cust['gross'])}}</td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('',$cust['net'])}}</td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('',$cust['total'])}}</td>
    </tr>
    @endforeach
@else
    <h4 class="text-center">No Data Found!</h4>
@endif