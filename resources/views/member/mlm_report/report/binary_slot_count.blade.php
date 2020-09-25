<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-sitemap"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Binary Slot Count Report</span>
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
                                @if(count($tree_level) >= count($tree_level_r))
                                    @foreach($tree_level as $key => $value)
                                    <th>LEVEL {{$key}}<br>(L/R)</th>
                                    @endforeach 
                                @else
                                    @foreach($tree_level_r as $key => $value)
                                    <th>LEVEL {{$key}}<br>(L/R)</th>
                                    @endforeach 
                                @endif   
                                <th>Total</th>       
                            </tr>     
                        </thead>
                        <tbody>
                            <?php
                            if(count($tree_r) >= $tree)
                            {
                                $t = $tree_r;
                            }
                            else
                            {
                                $t = $tree;
                            }
                            ?>
                            @foreach($t as $key => $value)
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

                                <?php $l_t = 0; $r_t = 0; ?>
                                @if(count($tree_level) >= count($tree_level_r))
                                    @foreach($tree_level as $key2 => $value2)
                                        <?php
                                            $l = 0;
                                            $r = 0;
                                            if(isset($tree[$key][$key2]))
                                            {
                                                $l = $tree[$key][$key2];
                                            }
                                            else
                                            {
                                                $l = 0;
                                            }

                                            if(isset($tree_r[$key][$key2]))
                                            {
                                                $r = $tree_r[$key][$key2];
                                            }
                                            else
                                            {
                                                $r = 0;
                                            }
                                        ?>
                                            <td>{{$l}}/{{$r}}</td>
                                            <?php $l_t += $l; $r_t += $r ?>
                                    @endforeach  
                                @else
                                    @foreach($tree_level_r as $key2 => $value2)
                                        <?php
                                            $l = 0;
                                            $r = 0;
                                            if(isset($tree[$key][$key2]))
                                            {
                                                $l = $tree[$key][$key2];
                                            }
                                            else
                                            {
                                                $l = 0;
                                            }

                                            if(isset($tree_r[$key][$key2]))
                                            {
                                                $r = $tree_r[$key][$key2];
                                            }
                                            else
                                            {
                                                $r = 0;
                                            }
                                        ?>
                                        <td>{{$l}}/{{$r}}</td>
                                    @endforeach 
                                @endif    
                                <td>{{$l_t}}/{{$r_t}}</td>
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