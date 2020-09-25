@extends('member.layout')

@section('css')
<link rel="stylesheet" type="text/css" href="{{Request::input('pdf') == 'true' ? public_path() : ''}}/assets/card/style.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">  
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-4">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading clearfix">
                <form class="global-submit filter_search" action="/member/mlm/card/filter" method="post">
                {!! csrf_field() !!}
                <div class="col-md-12">
                    <label>Filter By:</label>
                    <select class="form-control" name="membership_id">
                        @foreach($membership as $key => $value)
                        <option value="{{$value->membership_id}}">{{$value->membership_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label>Status</label>
                    <select class="form-control" name="card_status">
                        <option value="0">Pending Cards</option>
                        <option value="1">Printed Cards</option>
                        <option value="2">Pre-printing Cards</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <hr>
                    <button class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<div class="panel panel-default panel-block panel-title-block panel-gray col-md-12">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading clearfix">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Membership Code</th>
                            <th>Issued</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="append_filter">
                        <tr>
                            <td class="text-center" colspan="4">Select filter first.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>               
@endsection

@section('script')
<script type="text/javascript">
    function submit_done (data) 
    {
        // body...
        if(data.status == 'succes')
        {
            $('.append_filter').html(data.append);
        }
        else if (data.status == 'success_done')
        {
            $('.filter_search').submit();
            // location.reload();
        }
    }
</script>
@endsection