<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Top Earners Report</span>
      <span class="info-box-number"></span>
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
                                <th>Rank</th>
                                <th>Slot</th>
                                <th>Member</th>
                                <th>Amount</th>    
                            </tr>  
                        </thead>
                        <tbody>
                            <?php $rank = 1; ?>
                            @foreach($income_top as $key => $value)
                            <tr>
                                <td>{{$rank}}</td>
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
                                    @if(isset($slot[$key]))
                                        
                                        <a href="javascript:" class="popup" link="/member/customer/customeredit/{{$slot[$key]->customer_id}}" size="lg">
                                        {{name_format_from_customer_info($slot[$key])}}
                                        </a>
                                        
                                    @else
                                        {{$key}}
                                    @endif 
                                </td>
                                <td>{{currency('PHP', $value)}}</td>
                            </tr>
                            <?php $rank++;?>
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