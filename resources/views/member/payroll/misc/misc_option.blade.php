@foreach($_data as $data)
<option value="{{$data['id']}}" {{$selected == $data['id'] ? 'selected="selected"':''}} {{isset($data['attr']) ? $data['attr'] : ''}}>{{$data['name']}}</option>
@endforeach