@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-money"></i>
            <h1>
                <span class="page-title">Encashment - Currency</span>
                <small>
                    You can set your encashment convertion here
                </small>
            </h1>
        </div>
    </div>
</div> 
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <form class="global-submit" method="post" action="/member/mlm/encashment/currency/update">
            {!! csrf_field() !!}
                <table class="table table-condensed table-bordered">
                        <tr>
                            <td colspan="20">
                                <span class="pull-right"><button class="btn btn-primary">Save</button></span>
                            </td>
                        </tr>
                        <tr>
                            <th>ISO</th>
                            <th>Country</th>
                            <th>Enable</th>
                            <th>Convertion</th>
                        </tr>
                    @foreach($country as $key => $value)
                        @if(isset($currency_set[$value->iso]))
                        <?php 
                            $c_set = $currency_set[$value->iso];
                        ?>
                        <tr>
                            <td>{{$value->iso}}
                                <input type="hidden" name="iso[{{$value->iso}}]" value="{{$value->iso}}">
                            </td>
                            <td>{{$value->name}}</td>
                            <th><input type="checkbox" name="en_cu_active[{{$value->iso}}]" value="0"
                            @if($currency_set[$value->iso]->en_cu_active == 1)
                                checked
                            @endif
                            @if($c_set->en_cu_default == 1)
                                onclick="return false;"
                            @endif
                            ></th>
                            <th><input type="number" class="form-control" name="en_cu_convertion[{{$value->iso}}]" value="{{$currency_set[$value->iso]->en_cu_convertion}}"
                            @if($c_set->en_cu_default == 1)
                                readonly="readonly"
                            @endif
                            step="0.01"
                            ></th>
                        </tr>
                        @else
                        <tr>
                            <td>{{$value->iso}}
                                <input type="hidden" name="iso[{{$value->iso}}]" value="{{$value->iso}}">
                            </td>
                            <td>{{$value->name}}</td>
                            <th><input type="checkbox" name="en_cu_active[{{$value->iso}}]" value="0"></th>
                            <th><input type="number" class="form-control" name="en_cu_convertion[{{$value->iso}}]" step="0.01"></th>
                        </tr>
                        @endif
                    @endforeach
                </table>
            </form>
        </div>
    </div>
</div>        
@endsection
@section('script')
<script type="text/javascript">
    function submit_done(data)
    {
        if(data.status == 'success')
        {
            toastr.success(data.message);
        }
        else
        {
            toastr.warning(data.message);
        }
    }
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection
