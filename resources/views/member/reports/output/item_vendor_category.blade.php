<div class="report-container">
  <div class="wrapper1"><div class="div1"></div></div>
  <div class="wrapper2">
      <div class="div2 table-reponsive" style="width: 100%;">
      <div class="panel panel-default panel-block panel-title-block panel-reportss load-data" style="width: 100%">
          <div class="panel-heading load-content">
             @include('member.reports.report_header')
             <div class="table-reponsive">
             		<table class="table table-condensed collaptable">
                  <h4><strong>{{$item_name}}</strong></h4>
                  <thead>
                    <tr>
                      <th>Vendor</th>
                      <th class="text-center">Time purchase to Vendor</th>
                      <th class="text-right">Total Amount Purchase</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($_report) > 0)
                      @foreach($_report as $report)
                      <tr>
                        <td>{{$report['vendor_name']}}</td>
                        <td class="text-center">{{$report['times_purchase']}}</td>
                        <td class="text-right">{{currency("PHP ",$report['total_amount_purchase'])}}</td>
                      </tr>
                      @endforeach
                    @else
                    <tr><td colspan="3" class="text-center">NO TRANSACTION YET</td></tr>
                    @endif
                  </tbody>
             		</table>
             	</div>
              <h5 class="text-center">---- {{$now or ''}} ----</h5>
          </div>
      </div>
    </div>
  </div>
</div>