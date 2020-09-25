<div class="col-sm-12">
    <table class="digima-table">
        <thead >
            <tr>
                <th style="width: 10px" class="text-center">#</th>
                <th class="text-center" style="width: 100px">ITEM DETAILS</th>
                <th class="text-center" style="width: 300px">ITEM DESCRIPTION</th>
                <th class="text-center" style="width: 100px;">ACTUAL STOCKS</th>
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
                            <label >{{$empties["item_actual_stock_um"]}}</label>
                        </td>
                    </tr>
                    @endforeach
                @endif
            @else
            <tr>
                <td colspan="3" class="text-center">NO ITEM</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>