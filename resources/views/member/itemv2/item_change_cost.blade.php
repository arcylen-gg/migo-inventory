<form class="global-submit form-horizontal" role="form" action="{{$action or ''}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title"><i class="fa fa-calculator"></i> Item's Cost Changed</h4>
        <input type="hidden" name="new_cost" value="{{$new_cost}}">
        <input type="hidden" name="item_id" value="{{$item_id}}">
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body">
                <div class="form-horizontal">
                    <label>Do you want to update the item record with the new cost ?</label> <br><br>
                    <table class="table-striped table-condensed" width="100%">
                        <tbody>
                        <tr>
                            <td width="50%">Current Cost:</td>
                            <td>{{currency("Php",$item_data->item_cost)}}</td>
                        </tr>
                        <tr>
                            <td width="50%">New Cost:</td>
                            <td>{{currency("Php",$new_cost)}}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td width="50%"><label><input type="radio" class="change-cost" data-value="yes" value="yes" name="change_cost"> Yes</label></td>
                                <td width="50%"><label><input type="radio" class="change-cost" data-value="no" value="no" checked name="change_cost"> No</label></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
                <div class="form-horizontal change-price-form" style="display: none;">
                    <label>Do you want to update the price for this item based on the new cost ?</label> <br><br>
                      <table class="table-striped table-condensed" width="100%">
                        <tbody>
                        <tr>
                            <td width="50%">Current price:</td>
                            <td>{{currency("Php",$item_data->item_price)}}</td>
                        </tr>
                        <tr>
                            <td width="50%">Markup on current price is:</td>
                            <td>{{ $new_cost == 0 ? 0.00 : (round((($item_data->item_price - $new_cost) / $new_cost) * 100,2)) }} %</td>
                        </tr>
                        <tr class="{{$markup = ($item_data->item_cost == 0 ? 0 : (($item_data->item_price - $item_data->item_cost) / $item_data->item_cost) * 100)}}">
                            <td width="50%">New price:</td>
                            <td>{{currency("Php",($new_cost * ($markup/100)) + $new_cost)}}</td>
                        </tr>
                        <tr>
                            <td width="50%">Markup on new price is:</td>
                            <td>{{round($markup,2) }} %</td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td width="50%"><label><input type="radio" class="change_price price-yes" value="yes" data-value="yes" name="change_price"> Yes</label></td>
                                <td width="50%"><label><input type="radio" class="change_price price-no" value="no" data-value="no" checked name="change_price"> No</label>
                                <input type="hidden" name="new_price" value="{{($new_cost * ($markup/100)) + $new_cost}}">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
    </div>
</form>
<script type="text/javascript">
    $("body").on("click",".change-cost", function()
    {
        if($(this).attr("data-value") == 'yes')
        {
            $(".change-price-form").slideDown();
        }
        else
        {
            $(".change-price-form").slideUp();
        }
    });
</script>