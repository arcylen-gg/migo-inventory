@foreach($_sales['data'] as $key => $rep)
<tr>
    <td>
       <a href="#">{{$rep['monthStr']}}</a> 
    </td>
    <td class="text-center">
        {{$rep['customerCount']}}
    </td>
    <td class="text-center">
        {{$rep['totalOrder']}}
    </td>
    <td class="text-right">
        <span class="pull-left">PHP</span>{{currency('',$rep['totalSales'])}}
    </td>
</tr>
@endforeach