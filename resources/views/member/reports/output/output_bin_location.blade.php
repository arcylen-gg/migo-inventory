<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header');
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable table-bordered">
         		<tr>
              <th class="text-center" class="text-center">Item Name</th>
         			<th class="text-center">Inventory</th>
         		</tr>
         		<tbody>
              @if(count($_bin) > 0)
                @foreach($_bin as $bin)
                <tr>
                  <td class="text-center">{{$bin->item_name}}</td>
                  <td class="text-center">{{$bin->qty}}</td>
                </tr>
                @endforeach
              @else
              <tr>
                <td class="text-center" colspan="2">NO BIN SELECTED</td>
              </tr>
              @endif
         		</tbody>
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>