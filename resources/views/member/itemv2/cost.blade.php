<form class="global-submit form-horizontal" role="form" action="{{$action or ''}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title"><i class="fa fa-calculator"></i> LANDING COST COMPUTATION</h4>
        <div>Compute the landing cost of your items</div>
    </div>
    <div class="modal-body clearfix">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
                <thead style="text-transform: uppercase">
                    <tr>
                        <th class="text-center" width="300px;">Description</th>
                        <th class="text-center" width="150px">Type</th>
                        <th class="text-center" width="150px">Value</th>
                        <th class="text-right" >Total</th>
                    </tr>
                </thead>
                <tbody class="cost">
                    @if(count($_created_cost) > 0)
                        @foreach($_created_cost as $keyc => $created_lc)
                            <tr class="tr-cost">
                                <td class="text-center"> <input type="text" readonly="true" name="cost_name[]" class="form-control text-center" value="{{$created_lc['landing_cost_name']}}"> </td>
                                <td class="text-center">
                                    @if($keyc != 0)
                                    <select class="form-control cost-type compute" name="cost_type[]">
                                        <option value="fixed" {{$created_lc['landing_cost_type'] == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                        <option value="percentage" {{$created_lc['landing_cost_type'] == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                    </select>
                                    @else
                                    <select class="hidden" name="cost_type[]">
                                        <option value="fixed" selected>Fixed</option>
                                    </select>
                                    @endif
                                </td>
                                <td class="text-center"><input class="form-control text-center compute number-input {{$keyc == 0 ? 'cost-input-value' : ''}} cost-value" type="text" name="cost_rate[]" value="{{$created_lc['landing_cost_rate']}}"></td>
                                <td class="text-right">
                                    <label class="cost-amount">{{currency('',$created_lc['landing_cost_amount'])}}</label>
                                    <input type="hidden" name="cost_amount[]" class="input-cost-amount" value="{{$created_lc['landing_cost_amount']}}">
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @if(count($_landing_cost) > 0)
                            @foreach($_landing_cost as $key => $cost)
                            <tr class="tr-cost">
                                <td class="text-center"> <input type="text" readonly="true" name="cost_name[]" class="form-control text-center" value="{{$cost->default_cost_name}}"> </td>
                                <td class="text-center">
                                    @if($key != 0)
                                    <select class="form-control cost-type compute" name="cost_type[]">
                                        <option value="fixed" {{$cost->default_cost_type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                        <option value="percentage" {{$cost->default_cost_type == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                    </select>
                                    @else
                                    <select class="hidden" name="cost_type[]">
                                        <option value="fixed" selected>Fixed</option>
                                    </select>
                                    @endif
                                </td>
                                <td class="text-center"><input class="form-control text-center compute number-input {{$key == 0 ? 'cost-input-value' : ''}} cost-value" type="text" name="cost_rate[]" value="{{$key == 0 ? $new_cost : 0}}"></td>
                                <td class="text-right">
                                    <label class="cost-amount"></label>
                                    <input type="hidden" name="cost_amount[]" class="input-cost-amount">
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endif
                  <!--   <tr>
                        <td class="text-center">Purchase Cost</td>
                        <td class="text-center"></td>
                        <td class="text-center"><input class="form-control text-center" type="text" name="" value="1000"></td>
                        <td class="text-right">PHP 1,000.00</td>
                    </tr>
                    <tr>
                        <td class="text-center"><input class="form-control text-center" type="text" name="" value="Sample 01"></td>
                        <td class="text-center">
                            <select class="form-control">
                                <option selected>Percentage</option>
                                <option>Fixed</option>
                            </select>
                        </td>
                        <td class="text-center"><input class="form-control text-center" type="text" name="" value="5"></td>
                        <td class="text-right">PHP 50.00</td>
                    </tr>
                    <tr>
                        <td class="text-center"><input class="form-control text-center" type="text" name="" value="Sample 02"></td>
                        <td class="text-center">
                            <select class="form-control">
                                <option>Percentage</option>
                                <option selected>Fixed</option>
                            </select>
                        </td>
                        <td class="text-center"><input class="form-control text-center" type="text" name="" value="180"></td>
                        <td class="text-right">PHP 180.00</td>
                    </tr>
                    <tr>
                        <td class="text-center"><input class="form-control text-center" type="text" name="" value=""></td>
                        <td class="text-center">
                            <select class="form-control">
                                <option>Percentage</option>
                                <option selected>Fixed</option>
                            </select>
                        </td>
                        <td class="text-center"><input class="form-control text-center" type="text" name="" value=""></td>
                        <td class="text-right">PHP 0.00</td>
                    </tr> -->
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right text-bold" colspan="3">LANDING COST</td>
                        <td class="text-right text-bold"><label class="landing-cost-amount"></label></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Apply this Costing</button>
    </div>
</form>
<script type="text/javascript" src="/assets/member/js/landing_cost/landing_cost.js"></script>