@extends('tablet.layout')
@section('content')
<div class="form-group">
    <div class="col-md-12">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="panel panel-default panel-block panel-title-block" id="top">
            <div class="panel-heading">
                <div class="col-md-6 col-xs-6">
                    <i class="fa fa-tablet"></i>
                    <h1>
                        <span class="page-title">Tablet</span>
                        <small>
                            Dashboard
                        </small>
                    </h1>
                </div>
                <div class="col-md-6 col-xs-6 text-right">
                    <div class="col-md-12 ">
                        <label>{{$employee_name}}</label><br>
                        <label>{{$employee_position}}</label><br>
                        <a href="/tablet/logout">Logout</a>
                    </div>  
                    <div class="col-sm-4 pull-right">
                        <form class="select-sir" method="get">
                            <select class="choose-sir form-control" name="sir_id">
                                @foreach($_sirs as $sir)
                                    <option {{Session::get("sir_id") == $sir->sir_id ? 'selected' : '' }} value="{{$sir->sir_id}}">SIR #{{$sir->sir_id}}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default panel-block panel-title-block panel-gray">
          <div class="tab-content panel-body form-horizontal tablet-container">
            <div class="form-group">
              <div class="col-md-12">
                <a link="/tablet/submit_all_transaction" size="md" class="popup"> Close this S.I.R</a>
              </div>
            </div>
            <div class="form-group clearfix text-center">
                <div class="col-md-6 col-xs-6">
                  <h3>SIR No: <strong>{{sprintf("%'.05d\n", $open_sir->sir_id)}}</strong></h3>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h3>
                   <a link="/tablet/sir_inventory/{{Session::get('sir_id')}}" size="lg" class="form-control btn btn-primary popup">Inventory</a>
                   </h3>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h3>
                   <a link="/tablet/sir_reload/{{Session::get('sir_id')}}" size="md" class="form-control btn btn-primary popup">Reload</a>
                   </h3>
                </div>
            </div>
            <div class="form-group clearfix">
                <div class="col-md-6 col-xs-6">
                  <a class="btn btn-primary form-control" href="/tablet/invoice"><i class="fa fa-list-alt"></i> Credit Sales ({{$total_invoice_amount}})</a>
                </div>
                <div class="col-md-6 col-xs-6">
                  <a class="btn btn-primary form-control" href="/tablet/sales_receipt/list"><i class="fa fa-line-chart"></i> Cash Sales ({{$total_sales_receipt}})</a>
                </div>      
            </div>
            <div class="form-group clearfix">
                <div class="col-md-6 col-xs-6">
                    <a class="btn btn-primary form-control" href="/tablet/receive_payment"><i class="fa fa-money"></i> Collection ({{$total_receive_payment}})</a>
                </div>
                <div class="col-md-6 col-xs-6">
                    <a class="btn btn-primary form-control" href="/tablet/credit_memo"><i class="fa fa-credit-card"></i> Credit Memo ({{$total_cm}})</a>          
                </div>        
            </div>
            <div class="form-group clearfix">
                <div class="col-md-6 col-xs-6">
                    <a class="btn btn-primary form-control" href="/tablet/customer"><i class="fa fa-users"></i> Customer ({{$total_customer}})</a>          
                </div>  
                <div class="col-md-6 col-xs-6">
                    <a class="popup btn btn-primary form-control" link="/tablet/agent/edit/{{$employee_id}}" ><i class="fa fa-gears"></i> Account Setting </a>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
@section("script")
<script type="text/javascript">
$('body').on("change", ".choose-sir", function()
{
   $('.select-sir').submit();
}); 
    function submit_done_customer(data)
    {
        if(data.message == "success")
        {
            toastr.success("Success");
            $(".tablet-container").load("/tablet/dashboard .tablet-container")
        }
        else if(data.message == "error")
        {
            toastr.warning(data.error);
        }
    }
    function submit_done(data)
    {
        if(data.status == "success")
        {
            toastr.success("Success");
            location.href = "/tablet/dashboard";
        }
        else if(data.status == "error")
        {
            toastr.warning(data.status_message);
            $(data.target).html(data.view);
        }
    }
    $(".select-sir-no").globalDropList(
    { 
      hasPopup                : "false",
      width                   : "100%",
      placeholder             : "Search SIR...",
      no_result_message       : "No result found!",
      onChangeValue : function()
        {
           set_as_selected_sir($(this).val());
        }
    });
    function set_as_selected_sir($sir_id)
    {
        $(".modal_header").removeClass("hidden");
        $.ajax({
            url  : '/tablet/selected_sir',
            data : {sir_id : $sir_id},
            type : 'get',
            success : function()
            {
                toastr.success("Success");
                location.href = "/tablet/dashboard";
            }
        })
    }
</script>
@endsection