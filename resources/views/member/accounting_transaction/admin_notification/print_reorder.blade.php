
<body>
    <div class="form-group text-center">REORDER AS OF {{date('F d, Y h:i A')}}</div>
    <div class="form-group text-center">
        @foreach($_items_reorder as $item_reorder)
        <table class="table table-condensed table-bordered" style="width: 100%;border: 1px solid #000;padding: 5px">
            <thead>
                <tr><th colspan="5" class="text-center">ORDER FOR WAREHOUSE {{strtoupper($item_reorder['warehouse'])}}</th></tr>
            </thead>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">BARCODE</th>
                    <th class="text-center">ITEM NAME</th>
                    <th class="text-center">REORDER POINT</th>
                    <th class="text-center">REMAINING QTY</th>
                </tr>
            </thead>
            <tbody>
                @if(count($item_reorder['item']) > 0)
                    @foreach($item_reorder['item'] as $item)
                    <tr>
                        <td style="padding: 5px" class="text-center">{{$item['item_id']}}</td>
                        <td  style="padding: 5px" class="text-center">
                            <div class="barcodeimg" style="background-color: #fff; padding: 7.5px 0; margin-top: 10px;">
                                @if($item['item_barcode'] != '')
                                <img style="height: 55px;width: 200px;object-fit: contain;" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item['item_barcode'], 'EAN13') }}" alt="barcode"/>
                                @endif
                            </div>
                        </td>
                        <td  style="padding: 5px" class="text-center">{{$item['item_name']}}</td>
                        <td  style="padding: 5px" class="text-center">{{$item['item_reorder']}}</td>
                        <td  style="padding: 5px" class="text-center"><label>{{$item['item_qty']}}</label></td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="5">NO ITEM TO ORDER IN THIS WAREHOUSE</td></tr>
                @endif
            </tbody>
        </table>
        <br>
        <br>
        @endforeach
    </div>
</body>
<script type="text/javascript">
    window.print();
</script>