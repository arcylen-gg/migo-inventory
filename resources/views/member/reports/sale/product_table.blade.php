@if($item)
    @foreach($item as $key => $prod)
    <tr>
        <td><a href="#">{{$prod['category_name']}}</a></td>

        <td><a href="#">{{$prod["info"]->eprod_name}}</a></td>

        <td class="text-center">{{$prod['quantity_sold']}}</td>

        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('',$prod['gross_sales'])}}
        </td>

        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('PHP',$prod['discount'])}}
        </td>

        <td class="text-right">{{$prod['refund']}}</td>

        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('',$prod['net_sales'])}}
        </td>

        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('',$prod['tax'])}}
        </td>

        <td class="text-right">
            <span class="pull-left">PHP</span>{{currency('',$prod['total'])}}
        </td>
    </tr>
    @endforeach
@else
    <h4 class="text-center">No Data Found!</h4>
@endif