
<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">COMPLAN INCOME REPORT</span>
      <span class="info-box-number">Per Day</span>
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
        	<div class="table-reponsive">
        		<table class="table table-condensed table-bordered">
        			<thead>
                        <tr>
            				<th>Day</th>
                            @foreach($filter as $key => $value)
            				<th>{{$key}}</th>
                            @endforeach
                        </tr>
        			</thead>
        			<tbody>
        				@foreach($per_day as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            @foreach($filter as $key2 => $value2)
                                @if(isset($value[$key2]))
                                <td>{{currency('PHP', $value[$key2])}}</td>
                                @else
                                <td>{{currency('PHP', 0)}}</td>
                                @endif
                            @endforeach
                        </tr>    
        				@endforeach
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>      
<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">COMPLAN INCOME REPORT</span>
      <span class="info-box-number">Per Month</span>
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
        	<div class="table-reponsive">
        		<table class="table table-condensed table-bordered">
        			<thead>
                        <tr>
            				<th>Month</th>
            				@foreach($filter as $key => $value)
                            <th>{{$key}}</th>
                            @endforeach
                        </tr>
        			</thead>
        			<tbody>
        				@foreach($per_month as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            @foreach($filter as $key2 => $value2)
                                @if(isset($value[$key2]))
                                <td>{{currency('PHP', $value[$key2])}}</td>
                                @else
                                <td>{{currency('PHP', 0)}}</td>
                                @endif
                            @endforeach
                        </tr>        
	        				
        				@endforeach
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>      

<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">COMPLAN INCOME REPORT</span>
      <span class="info-box-number">Per Year</span>
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
        	<div class="table-reponsive">
        		<table class="table table-condensed table-bordered">
        			<thead>
                        <tr>
            				<th>Year</th>
            				@foreach($filter as $key => $value)
                            <th>{{$key}}</th>
                            @endforeach
                        </tr>
        			</thead>
        			<tbody>
        				@foreach($per_year as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            @foreach($filter as $key2 => $value2)
                                @if(isset($value[$key2]))
                                <td>{{currency('PHP', $value[$key2])}}</td>
                                @else
                                <td>{{currency('PHP', 0)}}</td>
                                @endif
                            @endforeach
                        </tr>   
        				@endforeach
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>    