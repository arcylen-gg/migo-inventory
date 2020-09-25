@extends('tablet.layout')
@section('content')
<div class="form-group">
  <div class="col-md-12">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div class="col-md-8 col-xs-6 ">
                <i class="fa fa-tablet"></i>
                <h1>
                    <span class="page-title">Tablet &raquo; Customer</span>
                    <small>
                    </small>
                </h1>
            </div>
            <div class="col-md-4  col-xs-6 text-right">
                <div class="col-md-12 text-left">
                    <label>{{$employee_name}}</label><br>
                    <label>{{$employee_position}}</label><br>
                    <a href="/tablet/logout">Logout</a>
                </div>  
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-block panel-title-block" id="top">
       <div class="tab-content panel-body form-horizontal tablet-container">
            <div id="invoice" class="tab-pane fade in active">
                <div class="form-group">
                    <div class="col-md-4 col-xs-6">
                      <!--   <a class="btn btn-primary form-control popup" link="/tablet/customer/modalcreatecustomer?stat=not-approved" size="lg">Create Customer</a> -->
                    </div>
                    <div class="col-md-8 col-xs-6 text-right">
                        <a href="/tablet/dashboard"><< Back to Dashboard</a>
                    </div> 
                </div>
                <div class="form-group tablet-container">
                    <div class="col-md-12">
                      <table class="table table-bordered table-condensed">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Customer</th>
                                  <th>Balance</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($_customer as $customer)
                              <tr style="color: {{$customer->approved == 1? '#000' : '#ff3333' }};">
                                <td>{{$customer->customer_id}}</td>
                                <td>
                                  @if($customer->company != null)
                                  {{$customer->company}}
                                  @else
                                  {{$customer->title_name}} {{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}} 
                                  {{$customer->suffix_name}}
                                  @endif
                                </td>
                                <td>{{currency("PHP",$customer->customer_opening_balance)}}</td>
                                  <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span>
                                      </button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li><a href="/tablet/create_invoices/add?sir_id={{Session::get('sir_id')}}&customer_id={{$customer->customer_id}}">Create Invoice</a></li>  
                                            <li><a href="/tablet/receive_payment/add?customer_id={{$customer->customer_id}}">Receive Payment</a></li>  
                                            <li><a href="/tablet/customer_details/{{$customer->customer_id}}">View Customer Details</a></li>                                        
                                        </ul>
                                    </div>
                                </td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
       </div>
    </div>
  </div>
</div>
@endsection
@section("script")
<script type="text/javascript">
    function submit_done_customer(data)
    {
        if(data.message == "success")
        {
            toastr.success("Success");
            $(".tablet-container").load("/tablet/customer .tablet-container")
        }
        else if(data.message == "error")
        {
            toastr.warning(data.error);
        }
    }
</script>
@endsection