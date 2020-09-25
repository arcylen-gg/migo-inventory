@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-user"></i>
            <h1>
                <span class="page-title">Agent List</span>
                <small>
                    List of sales agent.
                </small>
            </h1>
            <div class="text-right">
            <a class="btn btn-primary panel-buttons popup" link="/member/pis/agent/add" size="md" data-toggle="modal" data-target="#global_modal">Add Agent</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Agent</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived Agent</a></li>
                </ul>
            </div>
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control srch-warehouse-txt" placeholder="Start typing employee name">
                </div>
            </div>
        </div>

        <div class="form-group tab-content panel-body employee-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Agent ID</th>
                                <th>Agent Name</th>
                                <th>Agent Email</th>
                                <th>Agent Position</th>
                                <th>Agent Date Created</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_employee != null)
                            @foreach($_employee as $employee)
                            <tr>
                                <td>{{$employee->employee_id}}</td>
                                <td>{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</td>
                                <td >{{$employee->email}}</td>
                                <td >{{$employee->position_name}}</td>
                                <td>{{date('F d, Y', strtotime($employee->created_at))}}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                        <li><a href="/member/pis/agent/transaction/{{$employee->employee_id}}" >Agents Transaction</a> </li>
                                        <li><a link="/member/pis/agent/edit/{{$employee->employee_id}}" size="md" class="popup">Edit</a></li>
                                        <li><a link="/member/pis/agent/archived/{{$employee->employee_id}}/archived" size="md" class="popup">Archived</a> </li>
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
            <div id="archived" class="tab-pane fade in">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">                    
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Employee Email</th>
                                <th>Employee Code</th>
                                <th>Employee Date Created</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_employee_archived != null)
                            @foreach($_employee_archived as $employee_archived)
                            <tr>
                                <td>{{$employee_archived->employee_id}}</td>
                                <td>{{$employee_archived->first_name." ".$employee_archived->middle_name." ".$employee_archived->last_name}}</td>
                                <td >{{$employee_archived->email}}</td>
                                <td >{{$employee_archived->position_name}}</td>
                                <td>{{date('F d, Y', strtotime($employee_archived->created_at))}}</td>
                                <td class="text-center">
                                    <a link="/member/pis/agent/archived/{{$employee_archived->employee_id}}/restore" size="md" class="popup">Restore</a> 
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
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/employee.js"></script>
@endsection