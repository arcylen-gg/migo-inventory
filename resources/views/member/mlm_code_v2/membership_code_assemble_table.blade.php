@if(count($_item) > 0)
<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center">Item Name</th>
            <th class="text-center">Stocks</th>
            <th class="text-center" width="120px">Required</th>
        </tr>
    </thead>
    <tbody>
        
        	@foreach($_item as $item)
            <tr>
                <td class="text-center">{{ $item->item_sku }}</td>
                <td class="text-center">{{ $item->inventory_count }}</td>
                <td class="text-center">{!! $item->required !!}</td>
            </tr>
            @endforeach
    </tbody>
</table>
@endif

<div style="text-align: center; padding: 5px;">The membership available on this kit is <b>{{ strtoupper($membership->membership_name) }}</b></div>

@if($kit_quantity_limit == -1)
    <div style="text-align: center; padding: 0;">There is no item requirements to generate code for this membershit kit.</div>
@else
    @if($allowed == "true")
        <div style="text-align: center; padding: 0;">You can create up to <span style="font-weight: bold;">{{ $kit_quantity_limit }} MEMBERSHIP KIT</span> using your inventory.</div>
    @else
        <div style="color: red; text-align: center; padding: 0;">You don't have enough stocks to make this assembly.</div>
    @endif
@endif

<input type="hidden" class="allowed-assembly" value="{{ $allowed }}">