@extends('mlm.layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <div class="panel panel-default panel-block">
            <div class="list-group">
                <div class="list-group-item" id="responsive-bordered-table">
                    <div class="form-group">
                        <h4 class="section-title">
                        Unused Discount Card
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <th>Date Created</th>
                                <th>Membership Name</th>
                                <th class="hide"></th>
                            </thead>
                            @if(isset($unused_discount_car))
                                @if(count($unused_discount_car) >= 1)
                                    @foreach($unused_discount_car as $key => $value)
                                        <tr>
                                            <td>{{$value->discount_card_log_date_created}}</td>
                                            <td>{{$value->membership_name}}</td>
                                            <td class="hide">
                                                <a href="javascript:" class="btn btn-primary" onClick="show_pop_up({{$value->discount_card_log_id}})">Use</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="42"><center>No used discount card</center></td>
                                </tr>
                                @endif
                            @else
                            <tr>
                                <td colspan="42"><center>No used discount card</center></td>
                            </tr>
                            @endif
                            </table>
                        </div>
                    </div>
                </div>                  
            </div>
        </div>
        <div class="panel panel-default panel-block">
            <div class="list-group">
                <div class="list-group-item" id="responsive-bordered-table">
                    <div class="form-group">
                        <h4 class="section-title">
                        Used Discount Card
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <th>Date Created</th>
                                <th>Membership Name</th>
                                <th>Used On</th>
                                <th>Date Used</th>
                                <th>Expiration</th>
                                <th>Discount Card Code <br> <span style="color: green;"><small>Show this code in the cashier</small></span></th>
                            </thead>
                            @if(isset($used_discount_card))
                                @if(count($used_discount_card) >= 1)
                                    @foreach($used_discount_card as $key => $value)
                                        <tr>
                                            <td>{{$value->discount_card_log_date_created}}</td>
                                            <td>{{$value->membership_name}}</td>
                                            <td>{{$value->title_name}} {{$value->first_name}} {{$value->middle_name}} {{$value->last_name}} {{$value->suffix_name}}</td>
                                            <td>{{$value->discount_card_log_date_used}}</td>
                                            <td>{{$value->discount_card_log_date_expired}}</td>
                                            <td>{{$value->discount_card_log_code}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="42"><center>No used discount card</center></td>
                                </tr>

                                @endif
                            @else
                            <tr>
                                <td colspan="42"><center>No used discount card</center></td>
                            </tr>
                            @endif
                            </table>
                        </div>
                    </div>
                </div>                  
            </div>
        </div> 
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
function  show_pop_up(discount_card_log_id) {
    console.log(1);
    var link = '/mlm/report/discount_card/use?discount_card_log_id=' + discount_card_log_id;

    action_load_link_to_modal(link, 'md');
}
</script>
@endsection