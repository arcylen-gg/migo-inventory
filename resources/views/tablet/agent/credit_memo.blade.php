@extends('tablet.layout')
@section('content')
<div class="form-group">
    <div class="col-md-12">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <div class="panel panel-default panel-block panel-title-block" id="top">
            <div class="panel-heading">
               <div class="col-md-8 col-xs-6">
                    <i class="fa fa-tablet"></i>
                    <h1>
                        <span class="page-title">Tablet &raquo; Credit Memo</span>
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

        <!-- NO PRODUCT YET -->
        <div class="panel panel-default panel-block panel-title-block panel-gray">
            <div class="tab-content panel-body form-horizontal tablet-container">
                <div id="invoice" class="tab-pane fade in active">
                    <div class="form-group">
                        <div class="col-md-4 col-xs-6">
                            <a class="btn btn-primary form-control" href="/tablet/credit_memo/add?sir_id={{Session::get('sir_id')}}">Create Credit Memo</a>
                        </div>
                        <div class="col-md-8 col-xs-6 text-right">
                            <a href="/tablet/dashboard"><< Back to Dashboard</a>
                        </div> 
                    </div>
                    <div class="">
                        <table class="table table-bordered table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th>CM No</th>
                                    <th>Customer Name</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($_cm)
                                @foreach($_cm as $cm)
                                    <tr>
                                        <td>{{$cm->cm_id}}</td>
                                        <td>{{$cm->title_name." ".$cm->first_name." ".$cm->middle_name." ".$cm->last_name." ".$cm->suffix_name}}</td>
                                        <td>{{currency("PHP",$cm->cm_amount)}}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                              <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-custom">
                                                  <!-- <li ><a class="popup" link="/member/customer/view_cm/{{$cm->cm_id}}" size="lg">View CM</a></li> -->
                                                  <li ><a href="/tablet/credit_memo/add?id={{$cm->cm_id}}&sir_id={{Session::get('sir_id')}}">Edit CM</a></li>
                                              </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection