         <div class="container">
          <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                  <th><center>Customer Name</center></th>
                  <th><center>Slot No.</center></th>
                  <th><center>Current Rank</center></th>
                  <th><center>Current PERSONAL-PV</center></th>
                  <th><center>Required PERSONAL-PV</center></th>
                  <th><center>Comission</center></th>
                  <th><center>Status</center></th>
                </tr>
                </thead>
                <tbody>
                @foreach($_stairstep as $stairstep)
                  <tr>
                    <td><center>{{$stairstep->first_name}} {{$stairstep->middle_name}} {{$stairstep->last_name}}</center></td>
                    <td><center>{{$stairstep->slot_no}}</center></td>
                    <td><center>{{isset($stairstep->stairstep_name) ? $stairstep->stairstep_name : "---"}}</center></td>
                    <td style="{{$stairstep->processed_personal_pv >= $stairstep->processed_required_pv ? 'color:green' : 'color:red'}}"><center>{{$stairstep->processed_personal_pv}}</center></td>
                    <td><center>{{$stairstep->processed_required_pv}}</center></td>
                    <td><center>{{$stairstep->processed_earned}}</center></td>
                    <td style="{{isset($stairstep->processed_status) ? 'color:green' : ''}}"><center>{{isset($stairstep->processed_status) ? "Processed" : "Pending"}}</center></td>
                  </tr>
                @endforeach
            </tbody>
          </table>
         </div>