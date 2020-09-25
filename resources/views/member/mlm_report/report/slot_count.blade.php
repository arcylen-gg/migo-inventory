<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-sitemap"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Slot Count Report</span>
      <span class="info-box-number">Per Level</span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div> 
<br>
<br>
<br>
<br>
<br>
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
        	<div style="overflow-x:auto;">
        		<table class="table table-condensed table-bordered">
        			<thead>
        				<thead>
                            <tr>
                                <th>Slot</th>
                                <th>Member</th>
                                @foreach($tree_level as $key => $value)
                                <th>{{$key}}</th>
                                @endforeach  
                            </tr>          
                        </thead>
                        <tbody>
                            @foreach($tree as $key => $value)
                            <tr>
                                <td>
                                    <a href="javascript:" class="popup" link="/member/mlm/slot/view/{{$key}}" size="lg">
                                    @if(isset($slot[$key]))
                                        {{$slot[$key]->slot_no}}
                                    @else
                                        {{$key}}
                                    @endif
                                    </a>
                                </td>
                                 <td>
                                    <a href="javascript:" class="popup" link="/member/customer/customeredit/{{$slot[$key]->customer_id}}" size="lg">
                                    {{name_format_from_customer_info($slot[$key])}}
                                    </a>
                                </td>

                                @foreach($tree_level as $key2 => $value2)
                                    @if(isset($value[$key2]))
                                        <td>{{$value[$key2]}}</td>
                                    @else
                                        <td>0</td>
                                    @endif
                                @endforeach  
                            </tr>
                            @endforeach
                        </tbody>
        			</thead>
        			<tbody>
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>    