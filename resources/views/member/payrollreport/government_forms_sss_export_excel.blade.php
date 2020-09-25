<div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="3">TOTAL ER ACCOUNT</th>
                                <th class="text-center" colspan="1">{{ payroll_currency($contri_info["grand_total_sss_er"]) }}</th>
                                <th class="text-center" colspan="3">TOTAL EE ACCOUNT</th>
                                <th class="text-center " colspan="2">{{ payroll_currency($contri_info["grand_total_sss_ee"]) }}</th>
                            </tr>
                             <tr>
                                <th class="text-center" colspan="3">TOTAL EC AMOUNT</th>
                                <th class="text-center" colspan="1">{{ payroll_currency($contri_info["grand_total_sss_ec"]) }}</th>
                                <th class="text-center" colspan="3">TOTAL MEDICARE AMOUNT</th>
                                <th class="text-center" colspan="2"></th>
                            </tr>
                            <tr>
                                <th class="text-center" colspan="3">TOTAL COUNT</th>
                                <th class="text-center" colspan="1">{{ count($contri_info["_employee_contribution"]) }}</th>
                                <th class="text-center" colspan="3">GRAND TOTAL</th>
                                <th class="text-center" colspan="2">{{ payroll_currency($contri_info["grand_total_sss_ee_er"]) }}</th>
                            </tr>
                            <tr >
                                <th class="text-center th4">#</th>
                                <th class="text-center th4">SSS NO.</th>
                                <th class="text-center th4">LAST NAME</th>
                                <th class="text-center th4">FIRST NAME</th>
                                <th class="text-center th4">MIDDLE NAME</th>
                                <th class="text-center th4">COVERED</th>
                                <th class="text-center th4">EE SHARE</th>
                                <th class="text-center th4">ER SHARE</th>
                                <th class="text-center th4">EC SHARE</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contri_info["_employee_contribution"] as $key => $contribution)
                            <tr>
                                <td class="text-center">{{ $contribution->count }}</td>
                                <td class="text-center">{{ $contribution->payroll_employee_sss }}</td>
                                <td class="text-center">{{ $contribution->payroll_employee_last_name }}</td>
                                <td class="text-center">{{ $contribution->payroll_employee_first_name }}</td>
                                <td class="text-center">{{ $contribution->payroll_employee_middle_name }}</td>
                                <td class="text-center">{{ $contribution->period_covered }}</td>
                                <td class="text-center">{{ payroll_currency($contribution->total_sss_ee) }}</td>
                                <td class="text-center">{{ payroll_currency($contribution->total_sss_er) }}</td>
                                <td class="text-center">{{ payroll_currency($contribution->total_sss_ec) }}</td>
                                
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