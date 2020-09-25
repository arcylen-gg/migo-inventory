@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">E-Wallet (Adjust)</span>
                <small>
                   This is for viewing and adjusting wallet of customer/slots.
                </small>
            </h1>
        </div>
        <a class="pull-right btn btn-primary" href="/member/mlm/wallet">Back</a>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
            <form class="global-submit" action="/member/mlm/wallet/adjust/submit" method="post">
            {!! csrf_field() !!}
               <label for="">Slot</label>
                <select class="form-control chosen-slot input-sm pull-left" name="slot_id" data-placeholder="Select Slot Sponsor" >
                    <option value=""></option>
                    @if(count($_slots) != 0)
                        @foreach($_slots as $slot)
                            <option value="{{$slot->slot_id}}">{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}} ({{$slot->slot_no}})</option>
                        @endforeach
                    @endif
                </select>
                <br>
                <label>Amount</label>
                <input type="number" class="form-control" name="wallet_log_amount">
                <label>Notification</label>
                ​<textarea class="form-control" rows="5" name="wallet_log_details"></textarea>
                <hr/>
                <label>Password</label>
                <input type='password' class="form-control" name="password">
                <hr />
                <button class="btn btn-primary pull-right">Confirm</button>
            </form>    
            </div>
        </div>
    </div>
</div>
<div class="col-md-1">
    
</div>              
    
</div> 
@endsection

@section('script')

<script type="text/javascript">
    $(".chosen-slot").chosen({no_results_text: "The slot doesn't exist."});
    $('.chosen-discount').chosen({no_results_text: "The discount card holder doesn't exist."})
    function submit_done (data) {
        // body...
        console.log(1);
        if(data.status == 'warning')
        {
            toastr.warning(data.message);
        }
        else if(data.status == 'success')
        {
            toastr.success(data.message);
            location.href = '/member/mlm/wallet';
        }
    }
</script>
@endsection
