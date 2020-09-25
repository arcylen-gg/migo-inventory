<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
    <div class="panel-heading load-content">
      @include('member.reports.report_header')
      <div class="table-reponsive">
        <table class="table table-condensed collaptable">
          <tr>
            <th>Vendor Name</th>
            <th class="text-right">Balance</th>


          </tr>
          <tbody>
            @foreach($_vendor as $key => $vendor)
              <tr>
                <td class="text-left"><a target="_blank" href="/member/report/vendor/detailed/{{$vendor->vendor_id}}" >{{$vendor->vendor_company == '' ? ucfirst($vendor->vendor_title_name." ".$vendor->vendor_first_name." ".$vendor->vendor_middle_name." ".$vendor->vendor_last_name." ".$vendor->vendor_suffix_name) : $vendor->vendor_company}}</a></td>
                <td class="text-right"><text class="total-report"><b>{{currency('PHP', $vendor->amt_total)}}</b></text></td>
              </tr>
            @endforeach
            <tr bgcolor="#c2bcbc">
                <td ><b>Total Amount</b></td>
                <td class="text-right"><b>{{currency('PHP', $vendor->amt_total_all_vendor)}}</b></td>
            </tr>
          </tbody>
        </table>
      </div>
      <h5 class="text-center">---- {{$now or ''}} ----</h5>
    </div>
  </div>
</div>