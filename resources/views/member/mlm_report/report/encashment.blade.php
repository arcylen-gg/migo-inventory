
<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-user"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">Encashment</span>
      <span class="info-box-number">Per Slot</span>
    </div>
  </div>
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
                                <th>
                                    Slot
                                </th>  
                                <th>
                                    Member
                                </th>
                                @foreach($request as $key => $value)
                                <th>{{$value}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($encashment as $key => $value)
                            <tr>
                                @if(isset($slot[$key]))
                                    <td>
                                        <a href="javascript:" class="popup" link="/member/mlm/slot/view/{{$slot[$key]->slot_id}}" size="lg">
                                        {{$slot[$key]->slot_no}}
                                        <a href="javascript:" class="popup" link="/member/mlm/slot/view/{{$key}}" size="lg">
                                    </td>
                                    <td>
                                        <a href="javascript:" class="popup" link="/member/customer/customeredit/{{$slot[$key]->customer_id}}" size="lg">
                                        {{name_format_from_customer_info($slot[$key])}}
                                        </a>
                                    </td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                                @foreach($request as $key2 => $value2)
                                    @if(isset($value[$key2]))
                                    <td>{{currency('PHP', $value[$key2])}}</td>
                                    @else
                                    <td>{{currency('PHP',0)}}</td>
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
<script type="text/javascript">
    show_currency();
</script>