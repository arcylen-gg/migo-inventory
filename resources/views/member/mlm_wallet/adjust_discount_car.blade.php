<div class="panel panel-default panel-block panel-title-block panel-gray col-md-4">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <div class="">
                    @if(isset($discount_card))
                    	<select class="form-control chosen-discount input-sm pull-left" name="slot_id" data-placeholder="Select Slot Sponsor" >
		                    <option value=""></option>
		                    @if(count($discount_card) != 0)
		                        @foreach($discount_card as $key => $value)
		                            <option value="{{$value->discount_card_log_id}}">{{$value->first_name}} {{$value->middle_name}} {{$value->last_name}} ({{$value->discount_card_log_id}})</option>
		                        @endforeach
		                    @endif
		                </select>
                    @else
                    <center>No Discount Card holder</center>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 