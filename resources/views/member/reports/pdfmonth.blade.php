
<style type="text/css">
    .table-bordered td, th{
        padding:10px;
        border:1px solid rgb(125,125,125);
    }
    
    .text-center{
        text-align:center;
    }
    .text-right{
        text-align: right;
    }
    .pull-left{
        float:left;
    }
    .page-break {
        page-break-after: always;
    }
</style>
<div class="page-break">
    
    <table class="table-bordered" cellspacing="0">
        <thead>
            <tr>
                <th class="text-center">Month</th>
                <th class="text-center">Orders</th>
                <th class="text-center">Gross sales</th>
                <th class="text-center">Discounts</th>
                <th class="text-center">Refunds</th>
                <th class="text-center">Net sales</th>
                <th class="text-center">Shipping</th>
                <th class="text-center">Tax</th>
                <th class="text-center">Total Sales</th>
            </tr>
        </thead>
            
            @foreach($data as $sale)
            <tr>
                <td>
                    {{$sale['monthStr']}}
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
    </table>
</div>