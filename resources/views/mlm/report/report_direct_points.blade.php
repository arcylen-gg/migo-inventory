@extends('mlm.layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <form method="POST">

        <div class="panel panel-default panel-block">
            <div class="list-group">
                @if(isset($points_log))
                <div class="list-group-item" id="responsive-bordered-table">
                    <div class="form-group">
                        <h4 class="section-title">
                        POINTS LOG
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <th>ID</th>
                                    <th>DATE</th>
                                    <th>SPONSOR</th>
                                    <th>POINTS</th>
                                    <th>TYPE</th>
                                </thead>
                                @if(count($points_log) >= 1)    
                                @foreach($points_log as $key => $value)
                                <tbody>
                                    <tr>
                                        <td>{{$value->points_log_id}}</td>
                                        <td>{{$value->points_log_date_claimed}}</td>
                                        <td>{{$value->points_log_Sponsor}}</td>
                                        <td>{{$value->points_log_points}}</td>
                                        <td>{{$value->points_log_type}}</td>
                                    </tr>
                                </tbody>    
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5"><center>No Points.</center></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                @else
                    <center>Points Not Found.</center>  
                @endif  
                <center>{!! $points_log->render() !!}</center>                     
            </div>
        </div> 

        </form> 
        <div>

    </div>
    </div>
</div>
@endsection