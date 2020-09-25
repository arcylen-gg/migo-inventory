<form id="bank-form" class="global-submit" method="POST" action="/mlm/slots/item_code_post">
    {!! csrf_field() !!}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Code</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-group">
                <label>Product Code</label>
                <input type="text" class="form-control" value="{{$item->item_activation_code}}" readonly>
                <input type="hidden" class="form-control" name="item_activation_code" value="{{$item->item_activation_code}}">
        </div>
        <div class="form-group">
                <label>Use to slot</label>
                <select name="slot_id" class="form-control">
                    @foreach($_slot as $slot)
                        <option value="{{$slot->slot_id}}">{{$slot->slot_no}} ({{$slot->membership_name}})</option>
                    @endforeach
                </select>
        </div>
        @foreach($_plan as $plan)
            
                @if($plan->marketing_plan_code == "STAIRSTEP")
                <div class="form-group">
                    STAIRSTEP: {{$item->STAIRSTEP}}
                </div>
                <div class="form-group">
                    STAIRSTEP GROUP: {{$item->STAIRSTEP_GROUP}}
                </div>
                @else
                <div class="form-group">
                    <?php $title_plan = $plan->marketing_plan_code; ?>
                    {{$plan->marketing_plan_code}}:{{$item->$title_plan}}
                </div>            
                @endif
        @endforeach
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Use Code</button>
    </div>
</form>