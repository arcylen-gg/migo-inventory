<div class="report-container">
  <div class="wrapper1"><div class="div1"></div></div>
  <div class="wrapper2">
      <div class="div2 table-reponsive" style="width: 100%;">
      <div class="panel panel-default panel-block panel-title-block panel-reportss load-data" style="width: 100%">
          <div class="panel-heading load-content">
             @include('member.reports.report_header')
             <div class="table-reponsive">
             		<table class="table table-condensed collaptable table-bordered">
                  <thead>
                    <tr>
                      <th>Item Name</th>
                      <th class="text-center">On Stock</th>
                      <th class="text-center">Incomplete</th>
                      <th class="text-center">Damaged</th>
                      <th class="text-center">Total</th>
                      <th class="text-center">Ordered</th>
                      <th class="text-center">Sold</th>
                      <th class="text-center">For Repair</th>
                    </tr>
                  </thead>
             		<tbody>
                  @if(count($_item_status) > 0)
                    @foreach($_item_status as $item_status)
                    <tr>
                      <td>{{$item_status->item_name}}</td>
                      <td class="text-center">{{$item_status->on_stock." pc"}}</td>
                      <td class="text-center">{{$item_status->incomplete." pc"}}</td>
                      <td class="text-center">{{$item_status->damaged." pc"}}</td>
                      <td class="text-center"><strong>{{$item_status->total_onstock." pc"}}</strong></td>
                      <td class="text-center">{{$item_status->ordered." pc"}}</td>
                      <td class="text-center">{{$item_status->sold." pc"}}</td>
                      <td class="text-center">{{$item_status->for_repair." pc"}}</td>
                    </tr>
                    @endforeach
                  @else
                  <tr>
                    <td class="text-center" colspan="20">NO ITEM YET</td>
                  </tr>
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