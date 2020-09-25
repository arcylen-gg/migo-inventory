<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        body
        {
            font-size: 13px;
            font-family: 'Titillium Web',sans-serif;
        }
    </style>
</head>
<body style="padding: 15px;border: 1px solid #a2a9b5; width: 50%">
    <div class="form-group text-center">
        <table style="width: 100%">
            <tr>
                <td>
                    <h2>
                        Picking Slip
                    </h2>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{{$wis->transaction_refnum != '' ? $wis->transaction_refnum : sprintf("%'.04d\n", $wis->new_inv_id)}}</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label>{{$wis->warehouse_name}}</label>
                </td>
            </tr>
        </table>
    </div>
    <br>

    <div class="form-group">
        <table style="width: 100%" class="table table-bordered">
            <thead>
                <tr>
                    <th>ITEMSKU</th>
                    <th>QTY</th>
                    <th class="text-center">BIN</th>
                </tr>
            </thead>
            <tbody>
                @if(count($wis_item) > 0)
                    @foreach($wis_item as $item)
                    <tr>
                        <td class="text-center">{{$item->item_sku}}</td>
                        <td class="text-center">{{$item->qty}}</td>
                        <td><small>{{$item->bin}}</small></td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="3" class="text-center">NO ITEMS</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <br>
    <br>
    <small>{{$footer or ''}}</small>
</body>
</html>
<style type="text/css">
    thead
    {
        border: 1px solid #000;
    }
</style>