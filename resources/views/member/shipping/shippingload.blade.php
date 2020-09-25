<div class="form-horizontal">
    <div class="form-group">
        <label for="" class="col-md-4">Shipping name</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="shipping_name_update" value="{{$ship->shipping_name}}" placeholder="Shipping Name" name=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-md-4">Shipping contact</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="shipping_contact_update" value="{{$ship->contact}}" placeholder="Shipping Contact" name=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-md-4">Unit of Measurement</label>
        <div class="col-md-8 ">
            <div class="input-group width-100">
                <span class="input-group-btn width-21">
                    <select class="form-control" id="measurement_update">
                        <option value="kg" {{$ship->measurement == 'kg'?'selected="selected"':''}}>kg</option>
                        <option value="lbs" {{$ship->measurement == 'lbs'?'selected="selected"':''}}>lbs</option>
                    </select>
                </span>
                <input type="number" placeholder="0" id="unit_update" value="{{$ship->unit}}" class="form-control text-right">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-md-4">Amount per Unit</label>
        <div class="col-md-8">
            <div class="input-group width-100">
                <span class="input-group-btn width-21">
                    <select class="form-control selectpicker" id="currency_update">
                        <option value="PHP" {{$ship->currency == 'PHP'?'selected="selected"':''}}>PHP</option>
                        <option value="USD" {{$ship->currency == 'USD'?'selected="selected"':''}}>USD</option>
                    </select>
                </span>
                <input type="number" placeholder="0" id="fee_update" step="any"  value="{{$ship->shipping_fee}}" class="form-control text-right">
            </div>
        </div>
    </div>
</div>