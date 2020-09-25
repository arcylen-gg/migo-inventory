@if($_sales['data'])
    @foreach($_sales['data'] as $key => $sale)
    <tr>
        <td>
            <a href="#">{{$sale['monthStr']}}</a>
        </td>
        <td class="text-center">
            {{$sale['totalOrder']}}
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($sale['totalGross'],2)}}
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($sale['totalDiscount'],2)}}
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($sale['totalRefund'],2)}}
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($sale['totalNet'],2)}}
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($sale['totalShipping'],2)}}
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($sale['totalTax'],2)}}
        </td>
        <td class="text-right">
            <span class="pull-left">PHP</span>{{number_format($sale['totalSales'],2)}}
        </td>
    </tr>
    @endforeach
@else
    <h4 class="text-center">No Data Found!</h4>
@endif