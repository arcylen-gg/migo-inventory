@extends('tablet.layout')
@section('content')

<div class="form-group">
  <div class="col-md-12">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div class="col-md-8 col-xs-6">
                <i class="fa fa-tablet"></i>
                <h1>
                    <span class="page-title">Tablet &raquo; Collection</span>
                    <small>
                    </small>
                </h1>
            </div>
            <div class="col-md-4 col-xs-6 text-right">
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
                        <a class="btn btn-primary form-control" href="/tablet/receive_payment/add">Collect Payment</a>
                    </div>
                    <div class="col-md-8 col-xs-6 text-right">
                        <a href="/tablet/dashboard"><< Back to Dashboard</a>
                    </div> 
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                      <table class="table table-bordered table-condensed">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Customer</th>
                                  <th>Receive Payment</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach($_receive_payment as $rp)
                              <tr>
                                <td>{{$rp->rp_id}}</td>
                                <td>
                                  @if($rp->company != null)
                                  {{$rp->company}}
                                  @else
                                  {{$rp->title_name}} {{$rp->first_name}} {{$rp->middle_name}} {{$rp->last_name}} 
                                  {{$rp->suffix_name}}
                                  @endif
                                </td>
                                <td>{{currency("PHP",$rp->rp_total_amount)}}</td>
                                  <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action <span class="caret"></span>
                                      </button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li><a href="/tablet/receive_payment/add?id={{$rp->rp_id}}">Edit Payment</a></li>                                        
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