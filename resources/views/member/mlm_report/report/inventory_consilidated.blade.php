<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
        	<div style="overflow-x:auto;">
        		<table class="table table-condensed table-bordered">
        			<thead>
        				<thead>
                            <tr>
                                <th>Item Name</th>  
                                @foreach($filter as $key => $value)  
                                    <th>{{$key}}</th> 
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventory as $key => $value)
                            <tr>
                                <td>{{$key}}</td>
                                @foreach($value as $key2 => $value2)
                                <td>
                                    {{$value2}}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
        			</thead>
        			<tbody>
                        <tr>
                            <td>Total Item Sold</td>
                            <td>{{$item_sold}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total Sales</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$total}}</td>
                        </tr>
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div> 

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <div style="overflow-x:auto;">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <thead>
                            <tr>
                                <th>Members Who Purchased</th>  
                                <th>Type of Membership</th>
                                <th>Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($info as $key => $value)
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$value->membership_name}}</td>
                                <td>{{$value->slot_no}}</td>
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

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <div style="overflow-x:auto;">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <thead>
                            <tr>
                                <th colspan="2">Breakdown Per Membership Type</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($per_membership as $key => $value)
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$value}}</td>
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