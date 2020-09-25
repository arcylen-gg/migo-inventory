<div class="modal-body clearfix">
                  <div class="form-group tab-content panel-body employee-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                            @foreach($_month as $key => $month)
                            <tr>
                                <td>{{ $month["month_name"] }}</td>
                                                        <td class="text-center"> 
                                    <div class="dropdown">
                                        <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                                        <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li>
                                                <a href="javascript: action_load_link_to_modal('/member/payroll/reports/view_bir_forms/{{ $year_today }}/{{ $month["month_value"] }}/{{$company}}','lg')"><i class="fa fa-search"></i>&nbsp;View</a>
                                            </li>
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