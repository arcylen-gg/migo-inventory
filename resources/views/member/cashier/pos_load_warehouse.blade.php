@foreach($_warehouse as $warehouse)
    <option warehouse-address="{{$warehouse->warehouse_address}}" value="{{$warehouse->warehouse_id}}">{{$warehouse->warehouse_name}}</option>
@endforeach