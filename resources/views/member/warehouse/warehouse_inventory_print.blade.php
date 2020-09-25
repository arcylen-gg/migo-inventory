<body>
<div  class="text-center">
	<h2>{{strtoupper($owner->shop_key)}}</h2>
	<h3>{{strtoupper($owner->warehouse_name)}}</h3>
	<div>{{ucwords($owner->warehouse_address)}}</div>
    <div>Manufacturer : {{$manufacturer_name or 'All'}}</div>
	<div>{{strtoupper($type)}}</div>
	<div>{{date('M d, Y')}}</div>
</div>
<br>
@if($type == 'bundle')
<table class="" style="width: 100%">
	<thead>
            <tr>
                <th style="width: 10px">#</th>
                <th style="width: 100px">ITEM DETAILS</th>
                <th style="width: 300px">ITEM DESCRIPTION</th>
                <th style="width: 100px;">ACTUAL STOCKS</th>
                @if($pis != 0)
                <th style="width: 100px;">SIR STOCKS</th>
                <th style="width: 100px;">ON HAND STOCKS</th>
                @endif
            </tr>
        </thead>
        <tbody class="draggable">
            @if(count($warehouse_item_bundle) > 0)
                @if($warehouse_item_bundle != null)
                @foreach($warehouse_item_bundle as $keys => $w_bundle_item)
                <tr class="tr-draggable tr-draggable-html count_row">
                    <td>{{$keys+1}}</td>
                    <td>
                        <div><strong>{{$w_bundle_item["bundle_item_name"]}}</strong> </div>
                        <small>{{$w_bundle_item["bundle_item_bardcode"]}}</small>
                    </td>
                    <td >
                        <p> {{ $w_bundle_item['bundle_item_description'] }}</p>
                    </td>
                    <td>
                        {{$w_bundle_item["bundle_actual_stocks_um"]}}
                    </td>
                    @if($pis != 0)
                    <td>{{$w_bundle_item["total_stock_sir"]}}</td>
                    @endif
                    <td>
                        {{$w_bundle_item["bundle_current_stocks_um"]}}
                    </td>
                </tr>
                @endforeach
                @endif
            @else
            <tr>
                <td colspan="5" class="text-center">NO ITEM</td>
            </tr>
            @endif
        </tbody>
</table>
@endif

@if($type == 'bundle_empties')
<table class="" style="width: 100%">
    <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th style="width: 100px">ITEM DETAILS</th>
                <th style="width: 300px">ITEM DESCRIPTION</th>
                <th style="width: 100px;">ACTUAL STOCKS</th>
                @if($pis != 0)
                <th style="width: 100px;">SIR STOCKS</th>
                <th style="width: 100px;">ON HAND STOCKS</th>
                @endif
            </tr>
        </thead>
        <tbody class="draggable">
            @if(count($warehouse_item_bundle_empties) > 0)
                @if($warehouse_item_bundle_empties != null)
                @foreach($warehouse_item_bundle_empties as $keys => $w_bundle_item)
                <tr class="tr-draggable tr-draggable-html count_row">
                    <td>{{$keys+1}}</td>
                    <td>
                        <div><strong>{{$w_bundle_item["bundle_item_name"]}}</strong> </div>
                        <small>{{$w_bundle_item["bundle_item_bardcode"]}}</small>
                    </td>
                    <td >
                        <p> {{ $w_bundle_item['bundle_item_description'] }}</p>
                    </td>
                    <td>
                        {{$w_bundle_item["bundle_actual_stocks_um"]}}
                    </td>
                    @if($pis != 0)
                    <td>{{$w_bundle_item["total_stock_sir"]}}</td>
                    @endif
                    <td>
                        {{$w_bundle_item["bundle_current_stocks_um"]}}
                    </td>
                </tr>
                @endforeach
                @endif
            @else
            <tr>
                <td colspan="5" class="text-center">NO ITEM</td>
            </tr>
            @endif
        </tbody>
</table>
@endif
@if($type == 'inventory')
<table class="" style="width: 100%">
	 <thead >
            <tr>
                <th style="width: 10px">#</th>
                <th style="width: 100px">ITEM DETAILS</th>
                <th style="width: 300px">ITEM DESCRIPTION</th>
                <th style="width: 100px;">ACTUAL STOCKS</th>
                @if($pis != 0)
                <th style="width: 100px;">SIR STOCKS</th>
                <th style="width: 100px;">ON HAND STOCKS</th>
                @endif
            </tr>
        </thead>
        <tbody class="draggable">
            @if(count($_inventory) > 0)
                @if($_inventory != null)
                    @foreach($_inventory as $keys => $inventory)
                    <tr class="tr-draggable tr-draggable-html count_row">
                        <td>{{$keys+1}}</td>
                        <td>
                            <div><strong>{{$inventory["item_sku"]}}</strong> </div>
                            <small>{{$inventory["item_barcode"]}}</small>
                        </td>
                        <td>
                            <p>{{$inventory["item_description"]}}</p>
                        </td>
                        <td>
                            {{$inventory["item_actual_stock_um"]}}
                        </td>
                        @if($pis != 0)
                        <td>{{$inventory["sir_stock"]}}</td>
                        @endif
                        <td>
                           {{$inventory["less_stock_um"]}}
                        </td>
                    </tr>
                    @endforeach
                @endif
            @else
            <tr>
                <td colspan="5" class="text-center">NO ITEM</td>
            </tr>
            @endif
        </tbody>
</table>
@endif
@if($type == 'empties')
<table class="" style="width: 100%">
	  <thead >
            <tr>
                <th style="width: 10px">#</th>
                <th style="width: 100px">ITEM DETAILS</th>
                <th style="width: 300px">ITEM DESCRIPTION</th>
                <th style="width: 100px;">ACTUAL STOCKS</th>
            </tr>
        </thead>
        <tbody class="draggable">
            @if(count($_empties) > 0)
            @if($_empties != null)
                @foreach($_empties as $keys => $empties)
                <tr class="tr-draggable tr-draggable-html count_row">
                    <td>{{$keys+1}}</td>
                        <td>
                            <label class="count-select"></label>

                            <label class="count-select">
                                <div><strong>{{$empties["item_sku"]}}</strong> </div>
                                <small>{{$empties["item_barcode"]}}</small>
                            </label>
                        </td>
                        <td>
                            <p>{{$empties["item_description"]}}</p>
                        </td>
                    <td>
                        {{$empties["item_actual_stock_um"]}}
                    </td>
                </tr>
                @endforeach
            @endif
            @else
            <tr><td colspan="3" class="text-center">NO ITEM</td></tr>
            @endif
        </tbody>
</table>
@endif
</body>

<style type="text/css">
	tr 
    {
        page-break-inside: avoid; 
    }
    body 
    {
    	font-size: 12px;
    }

</style>