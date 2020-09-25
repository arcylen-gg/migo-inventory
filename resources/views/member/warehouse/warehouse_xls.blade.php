<html>
    <head>
        <style type="text/css">
            td
            {
                text-align: left;
            }
        </style>
    </head>

    <tr>
        <td>WAREHOUSE NAME: </td>
        <td>{{ $warehouse->warehouse_name }}</td>
    </tr>

    <tr>
        <td>Total Items available: </td>
        <td>{{ $quantity }}</td>
    </tr>

    <tr>
        <td></td>
    </tr>

    <tr>
        <td>Item Name</td>
        <td>Quantity</td>
        <td>Cost Price</td>
        <td>Selling Price</td>
        <td>Serial #</td>
    </tr>

    <!-- START LOOP -->
    @foreach($_item as $item)
    <tr>
        <td>{{ $item->item_name }}</td>
        {{-- <td>{{ $item->item_quantity }}</td> --}}
        <td>{{ $item->sum }}</td>
        <td>{{ $item->item_cost }}</td>
        <td>{{ $item->item_price }}</td>
        <td>{{ $item->serial_number }}</td>
    </tr>
    @endforeach
    <!-- END LOOP -->

</html>