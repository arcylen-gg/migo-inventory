@extends('member.layout')
<link href="/assets/select2/css/select2.min.css" rel="stylesheet" />
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Admin Dashboard &raquo; Group Approver</span>
                <small>
                select group.
                </small>
            </h1>
           <button class="btn btn-primary pull-right popup" link='/member/payroll/payroll_admin_dashboard/modal_create_group_approver' size='md'>Create Approver Group</button>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block load-data">
  <div>
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#active_group_employee"><i class="fa fa-star-o" aria-hidden="true"></i> &nbsp; Active</a></li>
      <li><a data-toggle="tab" href="#unactive_group_employee"><i class="fa fa-trash-o" aria-hidden="true"></i> &nbsp; Archived</a></li>
    </ul>

    <div class="tab-content">
      <div id="active_group_employee" class="tab-pane fade in active">
        <div style="padding : 10px;">
          @if(count($_group_approver) > 0)
          <table class="table table-bordered table-condensed table-striped table-hover">
            <thead>
               <th class="text-center">Approver Group Name</th>
               <th class="text-center">Approver Group Type</th>
               <th class="text-center">Approver Group Level</th>
               <th class="text-center">Action</th>
            </thead>
            <tbody>
               @foreach($_group_approver as $group_approver)
               <tr>
                 <td class="text-center">{{  $group_approver->payroll_approver_group_name  }}</td>
                 <td class="text-center">{{  $group_approver->payroll_approver_group_type  }}</td>
                 <td class="text-center">{{  $group_approver->payroll_approver_group_level  }}</td>
                 <td class="text-center">
                   <div class="dropdown">
                     <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                     <span class="caret"></span></button>
                     <ul class="dropdown-menu dropdown-menu-custom">
                       <li>
                         <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/modal_edit_group_approver/{{$group_approver->payroll_approver_group_id}}" size='md'><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                       </li>
                       <li>
                         <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/modal_archive_group_approver/{{$group_approver->payroll_approver_group_id}}?archive=1&group_name={{str_replace(' ','_',$group_approver->payroll_approver_group_name)}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archive</a>
                       </li>
                      </ul>
                    </div>
                  </td>
                </tr>
               @endforeach
            </tbody>
          </table>
          @else
          <div style="margin: 50px; text-align: center;">
            <h3>No Data Found</h3>
          </div>
          @endif
        </div>
      </div>
      <div id="unactive_group_employee" class="tab-pane fade">
        <div style="padding : 10px;">
          @if(count($_archived_group_approver) > 0)
          <table class="table table-bordered table-condensed table-striped table-hover">
            <thead>
               <th class="text-center">Approver Group Name</th>
               <th class="text-center">Approver Group Type</th>
               <th class="text-center">Approver Group Level</th>
               <th class="text-center">Action</th>
            </thead>
            <tbody>
               @foreach($_archived_group_approver as $group_approver)
               <tr>
                 <td class="text-center">{{  $group_approver->payroll_approver_group_name  }}</td>
                 <td class="text-center">{{  $group_approver->payroll_approver_group_type  }}</td>
                 <td class="text-center">{{  $group_approver->payroll_approver_group_level  }}</td>
                 <td class="text-center">
                   <div class="dropdown">
                     <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                     <span class="caret"></span></button>
                     <ul class="dropdown-menu dropdown-menu-custom">
                       <li>
                         <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/modal_edit_group_approver/{{$group_approver->payroll_approver_group_id}}" size='md'><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                       </li>
                       <li>
                         <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/modal_archive_group_approver/{{$group_approver->payroll_approver_group_id}}?archive=0&group_name={{str_replace(' ','_',$group_approver->payroll_approver_group_name)}}" size="sm"><i class="fa fa-refresh"></i>&nbsp; Restore</a>
                       </li>
                     </ul>
                   </div>
                 </td>
               </tr>
               @endforeach
            </tbody>
          </table>
          @else
          <div style="margin: 50px; text-align: center;">
            <h3>No Data Found</h3>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/assets/select2/js/select2.min.js"></script>
<script type="text/javascript">
  function reload_group_approver()
  {
    window.location.href = '/member/payroll/payroll_admin_dashboard/group_approver';
  }
</script>
@endsection('content')


