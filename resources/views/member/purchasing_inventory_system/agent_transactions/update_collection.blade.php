<form class="global-submit form-horizontal" role="form" action="/member/pis_agent/collection_update_submit" id="confirm_answer" method="post">
{!! csrf_field() !!}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Update Collection</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
    <div class="col-md-12">
        <h3>SIR #{{sprintf("%'.04d\n", $sir_id)}}</h3>
        <h4>Agent Name: <strong>{{ucfirst($collection_data->first_name." ".$collection_data->middle_name." ".$collection_data->last_name)}}</strong></h4>
    </div>
    <div class="col-md-12">
        <h3>Total Collection</h3>
        <h4>{{$collectibles}}</h4>
    </div>
    <div class="col-md-12">
        <label>Amount Remitted</label>
        <input type="text" class="form-control number-input" required value="{{$collection_data->agent_collection or ''}}" name="agent_collection">
    </div>
    <div class="col-md-12">
        <label>Collection Remarks</label>
        <textarea class="form-control textarea-expand" required  name="agent_remarks">{{$collection_data->agent_collection_remarks or ''}}</textarea>
    </div>
    <input type="hidden" name="sir_id" value="{{$sir_id}}">
</div>
<div class="modal-footer">
    <div class="col-md-6 col-xs-6"><button type="submit" class="btn btn-custom-blue form-control">Update</button></div>
    <div class="col-md-6 col-xs-6"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">Cancel</button></div>
</div>	
</form>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript">
$(document).on("keypress",".number-input", function(event)
    {
        if(event.which < 46 || event.which > 59) {
            event.preventDefault();
        } // prevent if not number/dot

        if(event.which == 46 && $(this).val().indexOf('.') != -1) {
            event.preventDefault();
        } // prevent if already dot

    });

$(document).on("change",".number-input", function()
{
    $(this).val(function(index, value) {         
        var ret = '';
        value = action_return_to_number(value);
        if(!$(this).hasClass("txt-qty")){
            value = parseFloat(value);
            value = value.toFixed(2);
        }
        if(value != '' && !isNaN(value)){
            value = parseFloat(value);
            ret = action_add_comma(value).toLocaleString();
        }

        return ret;
      });
});

    function action_add_comma(number)
    {
        number += '';
        if(number == ''){
            return '';
        }

        else{
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }
    function action_return_to_number(number = '')
    {
        number += '';
        number = number.replace(/,/g, "");
        if(number == "" || number == null || isNaN(number)){
            number = 0;
        }
        
        return parseFloat(number);
    }
</script>