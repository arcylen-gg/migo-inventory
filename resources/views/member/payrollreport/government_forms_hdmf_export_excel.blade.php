
<div class="modal-body clearfix">
        <div class="filter-result table-responsive ">
            <table class="table table-bordered table-condensed" style=" background-color:0066CC;">
                <thead>
                    <tr>
                        <th class="text-center" colspan="4">TOTAL ee/loan Amount:</th>
                        <th class="text-center" colspan="5">{{ payroll_currency($contri_info["grand_total_pagibig_ee"]) }}</th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="4">TOTAL er Amount</th>
                        <th class="text-center" colspan="5">{{ payroll_currency($contri_info["grand_total_pagibig_er"]) }}</th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="4">RECORD Count</th>
                        <th class="text-center" colspan="5">{{ count($contri_info["_employee_contribution"]) }}</th>
                    </tr>
                    <tr >
                        <th class="text-center th4">#</th>
                        <th class="text-center th4">PAG-IBIG NO.</th>
                        <th class="text-center th4">TIN(Optional)</th>
                        <th class="text-center th4">LAST NAME</th>
                        <th class="text-center th4">FIRST NAME</th>
                        <th class="text-center th4">MIDDLE NAME</th>
                        <th class="text-center th4">PERIOD COVERED</th>
                        <th class="text-center th4">EE SHARE</th>
                        <th class="text-center th4">ER SHARE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contri_info["_employee_contribution"] as $key => $contribution)
                    <tr>
                        <td class="text-center">{{ $contribution->count }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_pagibig }}</td>
                        <td class="text-center">Not Available</td>
                        <td class="text-center">{{ $contribution->payroll_employee_last_name }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_first_name }}</td>
                        <td class="text-center">{{ $contribution->payroll_employee_middle_name }}</td>
                        <td class="text-center">{{ $contribution->period_covered }}</td>
                        <td class="text-center">{{ payroll_currency($contribution->total_pagibig_ee) }}</td>
                        <td class="text-center">{{ payroll_currency($contribution->total_pagibig_er) }}</td>
                     </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>
    <style>
   th{
    background-color:#0066CC;
   }
   .th4{
    border: 5px solid #2C2C2C;
    background-color:#666666;
   }
   td{
    border: 5px solid #2C2C2C;
     background-color:#FBFBFB;
    
   }
   </style>
