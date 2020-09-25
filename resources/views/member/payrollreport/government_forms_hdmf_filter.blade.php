<div class="modal-body clearfix">
        <div class="filter-result table-responsive ">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">PAG-IBIG NO.</th>
                        <th class="text-center">LAST NAME</th>
                        <th class="text-center">FIRST NAME</th>
                        <th class="text-center">NAME EXT</th>
                        <th class="text-center">MIDDLE NAME</th>
                        <th class="text-center">PERIOD COVERED</th>
                        <th class="text-center">EE SHARE</th>
                        <th class="text-center">ER SHARE</th>
                        <th class="text-center">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($contri_info["_employee_contribution"]))
                    @foreach($contri_info["_employee_contribution"] as $key => $contribution)
                    <tr>
                        <td class="text-center">{{ $contribution->count }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_pagibig }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_last_name }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_first_name }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_suffix_name }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_middle_name }}</td>
                        <td class="text-center">{{ $contribution->period_covered }}</td>
                        <td class="text-center">{{ payroll_currency($contribution->total_pagibig_ee) }}</td>
                        <td class="text-center">{{ payroll_currency($contribution->total_pagibig_er) }}</td>
                        <td class="text-center" style="color: #76B6EC;">{{ payroll_currency($contribution->total_pagibig_ee_er) }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right" style="font-weight: bold;">GRAND TOTAL</td>
                        <td class="text-center" style="font-weight: bold;">{{ payroll_currency($contri_info["grand_total_pagibig_ee"]) }}</td>
                        <td class="text-center" style="font-weight: bold;">{{ payroll_currency($contri_info["grand_total_pagibig_er"]) }}</td>
                        <td class="text-center" style="color: #76B6EC; font-weight: bold;">{{ payroll_currency($contri_info["grand_total_pagibig_ee_er"]) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <a href="/member/payroll/reports/government_forms_hdmf_export_excel/{{$month}}/{{$company_id1}}/{{$year}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
        <a role="form" target="_blank" href="/member/payroll/reports/government_forms_hdmf_iframe/{{ $month }}/{{$company_id1}}/{{$year}}"><button class="btn btn-primary btn-custom-primary" type="submit">View PDF Form</button></a>&nbsp;&nbsp;
    </div>