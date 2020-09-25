@if(count($_record) > 0)
    <div class="text-center" style="color: green; padding: 20px;">
        <div>{{ number_format($record_success) }} Time Records Imported!</div>
        <div style="color: red;">{{ number_format($record_failed) }} Records Failed!</div>
        <div style="color: blue;">{{ number_format($record_incomplete) }} Incomplete Data!</div>
        <div style="color: blue;">{{ number_format($record_overwritten) }} Records has been Overwritten</div>
    </div>
    <table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center">NO.</th>
                <th class="text-center">Name</th>
                <th class="text-center">Date</th>
                <th class="text-center">Time In</th>
                <th class="text-center">Time Out</th>
                <th class="text-center" width="300px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($_record as $employee)
                <tr>    
                    <td class="text-center">{{ $employee->employee_no }}</td>
                    <td class="text-center">{{ $employee->employee_biometrics_name }}</td>
                    <td class="text-center">{{ date("M d, Y", strtotime($employee->employee_record[0]->date)) }}</td>
                    <td class="text-center">{{ $employee->employee_record[0]->time_in }}</td>
                    <td class="text-center">{{ $employee->employee_record[0]->time_out }}</td>
                    <td class="text-center">{!! $employee->employee_record[0]->status !!}</td>
                </tr>
                @foreach($employee->employee_record as $key_date => $date)
                    @if($key_date != 0)
                    <tr>    
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">{{ date("M d, Y", strtotime($date->date)) }}</td>
                        <td class="text-center">{{ $date->time_in }}</td>
                        <td class="text-center">{{ $date->time_out }}</td>
                        <td class="text-center">{!! $date->status !!}</td>
                    </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center" style="padding: 150px 0">NO DATA</div>
@endif
