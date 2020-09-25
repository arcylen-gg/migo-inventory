@if(count($_truck) > 0)
    @foreach($_truck as $truck)
        <option value="{{$truck->truck_id}}" {{isset($truck_id) ? ($truck_id == $truck->truck_id ? 'selected' : '') : ''}}>{{$truck->plate_number}}</option>
    @endforeach
@endif