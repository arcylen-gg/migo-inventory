<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th class="text-center" colspan="3">TOTAL PAYMENT</th>
            <th class="text-center" colspan="2">{{ payroll_currency($contri_info["grand_total_philhealth_ee_er"]) }}</th>
            <th class="text-center" colspan="2">TOTAL PS</th>
            <th class="text-center" colspan="2">{{ payroll_currency($contri_info["grand_total_philhealth_ee"]) }}</th>
        </tr>
         <tr>
            <th class="text-center" colspan="3">TOTAL COUNT</th>
            <th class="text-center" colspan="2">{{ count($contri_info["_employee_contribution"]) }}</th>
            <th class="text-center" colspan="2">TOTAL ES</th>
            <th class="text-center" colspan="2">{{ payroll_currency($contri_info["grand_total_philhealth_er"]) }}</th>
        </tr>
        <tr>
        	<th class="text-center th4">#</th>
            <th class="text-center th4">PHILHEALTH NO.</th>
            <th class="text-center th4">LAST NAME</th>
            <th class="text-center th4">FIRST NAME</th>
            <th class="text-center th4">NAME EXT</th>
            <th class="text-center th4">MIDDLE NAME</th>
            <th class="text-center th4">PERIOD COVERED</th>
            <th class="text-center th4">PS SHARE</th>
            <th class="text-center th4">ES SHARE</th>
        </tr>
    </thead>
    
    <tbody>
			@foreach($contri_info["_employee_contribution"] as $key => $contribution)
    	<tr>
    		<td class="text-center">{{ $contribution->count }}</td>
    		<td class="text-center">{{ $contribution->payroll_employee_philhealth }}</td>
    		<td class="text-center">{{ $contribution->payroll_employee_last_name }}</td>
    		<td class="text-center">{{ $contribution->payroll_employee_first_name }}</td>
    		<td class="text-center">{{ $contribution->payroll_employee_suffix_name }}</td>
    		<td class="text-center">{{ $contribution->payroll_employee_middle_name }}</td>
    		<td class="text-center">{{ $contribution->period_covered }}</td>
    		<td class="text-center">{{ payroll_currency($contribution->total_philhealth_ee) }}</td>
    		<td class="text-center">{{ payroll_currency($contribution->total_philhealth_er) }}</td>
    	</tr>
    	@endforeach
    </tbody>
      
</table>
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
