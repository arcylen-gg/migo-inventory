@extends('member.layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default panel-block">
            <div class="list-group">  
                <div class="table-responsive">
                <label>No. of membership</label>
                <input type="number" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>        

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default panel-block">
            <div class="list-group">  
                <div class="table-responsive">
                    <table class="table">
                            <tr>
                                <td>
                                    No of slot: {{$no_of_slot - 1}}
                                </td>
                            </tr>
                        @foreach($membership_info as $key => $value)
                            <tr>
                                <td>
                                    {{$value->membership_name}}({{$membership[$key]}}): {{currency('PHP', $membership_price[$key])}}
                                </td>
                            </tr>
                        @endforeach 
                            <tr>
                                <td>-Total cash in : {{$total_cashin}}</td>
                            </tr>
                    </table>
                </div>
            </div>
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div classs="panel panel-default panel-block">
            <div class="list-group">  
                <div class="table-responsive">
                    <table class="table">
                    <?php $total_cashout = 0; ?>
                    <?php $total_points = 0; ?>
                    @foreach($all_slot as $key => $value)
                    <tr>
                        <td>
                            <div class="col-md-12">Slot No: {{$value->slot_id}}</div>
                            <div class="col-md-6">
                                Income
                                @foreach($income[$value->slot_id] as $key2 => $value2)
                                    @if($value2 != null)
                                    <div class="col-md-12">{{$key2}} : {{$value2}}</div>
                                    <?php $total_cashout +=  $value2; ?>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                Points
                                @foreach($points[$value->slot_id] as $key2 => $value2)
                                    @if($value2 != null)
                                    <div class="col-md-12">{{$key2}} : {{$value2}}</div>
                                    <?php $total_points +=  $value2; ?>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            -Total Cashout : {{$total_cashout}} <br>
                            -Total Points : {{$total_points}}
                        </td>
                    </tr>
                    </table>
                </div>
            </div>        
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection
