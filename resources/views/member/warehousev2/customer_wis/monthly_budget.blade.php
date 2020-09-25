@if($monthly_budget && !isset($wis_monthly['_budget']))
<div class="monthly-budget-div">
    <hr>
    <div class="row clearfix">
        <div class="col-md-7 text-right digima-table-label">
            <div class="col-md-12">
                <select class="form-control text-right" name="budget_type">
                    <option value="Monthly Budget">Monthly Budget</option>
                    <option value="Quarterly Budget">Quarterly Budget</option>
                    <option value="Annually Budget">Annually Budget</option>
                </select>
            </div>
        </div>
        <div class="col-md-5 text-right digima-table-label">
        	<input type="text" class="change-val form-control text-right input-sm compute input-monthly-budget" name="budget_adjusted" value="{{$applied->fix_monthly_budget or ''}}">
        </div>
    </div>
    <div class="row clearfix" style="margin-top: 5px">
        <div class="col-md-7 text-right digima-table-label">
        	<div class="col-md-12">
        		<small class="hidden display-budget-text">Over/Under Budget for the Month of</small>
        		<input type="text" class="input-budget-text form-control text-right input-sm" name="current_budget_month" value="Over/Under Budget for the Month of ">
        	</div>
        </div>   
        <div class="col-md-5 text-right digima-table-label">
        	<input type="text" class="change-val input-budget-amount form-control text-right input-sm  current-budget-month-amount" name="current_budget_month_amount" value="">
        </div>
    </div>
    <div class="row clearfix" style="margin-top: 5px">
        <div class="col-md-7 text-right digima-table-label">
            <div class="col-md-12">
                <small class="hidden display-previous-budget-text">Previous Over/Under Remaining Budget for the Month of </small>
                <input type="text" class="input-previous-budget-text form-control text-right input-sm" name="prev_budget_month" value="Previous Over/Under Remaining Budget for the Month of ">
            </div>
        </div>
        <div class="col-md-5 text-right digima-table-label">
            <input type="text" class="change-val input-previous-budget-amount form-control text-right input-sm prev-budget-month-amount" name="prev_budget_month_amount" value="">
        </div>
    </div>
    <div class="row clearfix" style="margin-top: 5px">
        <div class="col-md-7 text-right digima-table-label">
            <div class="col-md-12">
                <small class="hidden display-adjusted-budget-text">Adjusted Over/Under Remaining Budget for the Month of </small>
                <input type="text" class="input-adjusted-budget-text form-control text-right input-sm" name="adj_budget_month" value="Adjusted Over/Under Remaining Budget for the Month of ">
            </div>
        </div>
        <div class="col-md-5 text-right digima-table-label">
            <input type="text" class="change-val input-adjusted-budget-amount form-control text-right input-sm adj-budget-month-amount" name="adj_budget_month_amount" value="">
        </div>
    </div>
    <div class="row clearfix" style="margin-top: 5px">
        <div class="col-md-7 text-right">
            <small>Less </small>
        </div>
        <div class="col-md-5">
            <select class="1111 form-control select-item-offset input-sm pull-left item-select" name="" >
                @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                <option class="hidden" value="" />
            </select>
        </div>
    </div> 
    <div class="row clearfix" style="margin-top: 5px">
        <div class="col-md-7 text-right"></div>
        <div class="col-md-5 text-right">
            <div class="item-listing">
                <table class="table table-condensed">
                    <tbody class="listing-offset-item">
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row clearfix" style="margin-top: 5px">
        <div class="col-md-7 text-right"></div>
        <div class="col-md-5 text-right digima-table-label">
            <input type="text" class="change-val form-control text-right input-sm offset-item-total item-less-amount" name="total_item_less_amount" value="(0.00)">
        </div>
    </div>
    <hr>
    <div class="row clearfix hidden" style="margin-top: 5px">
        <div class="col-md-7 text-right digima-table-label">
            <div class="col-md-12">
                <!-- <small>Total Adjusted Over/Under (Remaining) Budget for the Month of </small> -->
                <small class="hidden display-total-adjusted-text">Total Adjusted Over/Under Remaining Budget for the Month of </small>
                <input type="text" class="input-total-adjusted-text form-control text-right input-sm" name="total_budget_month" value="Total Adjusted Over/Under Remaining Budget for the Month of ">
            </div>
        </div>
        <div class="col-md-5 text-right digima-table-label">
            <!-- <input type="text" class="form-control text-right input-sm total-adj-amount" name="total_budget_month_amount" value=""> -->
            <input type="text" class="change-val input-total-adjusted-amount form-control text-right input-sm adj-budget-month-amount" name="total_budget_month_amount" value="">
        </div>
    </div>
</div>
@else
    @if(isset($wis_monthly['_budget']))
        @if(count($wis_monthly['_budget']) > 0)
        <div class="monthly-budget-div">
            <hr>
            <div class="row clearfix">
                <div class="col-md-7 text-right digima-table-label">
                    <div class="col-md-12">
                        <select class="form-control text-right" name="budget_type">
                            <option value="Monthly Budget" {{$wis_monthly['_budget']->budget_type == 'Monthly Budget' ? 'selected' : ''}}>Monthly Budget</option>
                            <option value="Quarterly Budget" {{$wis_monthly['_budget']->budget_type == 'Quarterly Budget' ? 'selected' : ''}}>Quarterly Budget</option>
                            <option value="Annually Budget" {{$wis_monthly['_budget']->budget_type == 'Annually Budget' ? 'selected' : ''}}>Annually Budget</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-5 text-right digima-table-label">
                    <input type="text" class="change-val form-control text-right input-sm compute input-monthly-budget" name="budget_adjusted" value="{{$wis_monthly['_budget']->budget_adjusted}}">
                </div>
            </div>
            <div class="row clearfix" style="margin-top: 5px">
                <div class="col-md-7 text-right digima-table-label">
                    <div class="col-md-12">
                        <small class="hidden display-budget-text">{{$wis_monthly['_budget']->current_budget_month or 'Over/Under Budget for the Month of '}}</small>
                        <input type="text" class="input-budget-text form-control text-right input-sm" name="current_budget_month" value="{{$wis_monthly['_budget']->current_budget_month or 'Over/Under Budget for the Month of '}}">
                    </div>
                </div>   
                <div class="col-md-5 text-right digima-table-label">
                    <input type="text" class="change-val input-budget-amount form-control text-right input-sm  current-budget-month-amount" name="current_budget_month_amount" value="{{$wis_monthly['_budget']->current_budget_month_amount}}">
                </div>
            </div>
            <div class="row clearfix" style="margin-top: 5px">
                <div class="col-md-7 text-right digima-table-label">
                    <div class="col-md-12">
                        <small class="hidden display-previous-budget-text"> {{$wis_monthly['_budget']->prev_budget_month or 'Previous Over/Under Remaining Budget for the Month of '}} </small>
                        <input type="text" class="input-previous-budget-text form-control text-right input-sm" name="prev_budget_month" value=" {{$wis_monthly['_budget']->prev_budget_month or 'Previous Over/Under Remaining Budget for the Month of '}}">
                    </div>
                </div>
                <div class="col-md-5 text-right digima-table-label">
                    <input type="text" class="change-val input-previous-budget-amount form-control text-right input-sm prev-budget-month-amount" name="prev_budget_month_amount" value="">
                </div>
            </div>
            <div class="row clearfix" style="margin-top: 5px">
                <div class="col-md-7 text-right digima-table-label">
                    <div class="col-md-12">
                        <small class="hidden display-adjusted-budget-text"> {{$wis_monthly['_budget']->adj_budget_month or 'Adjusted Over/Under Remaining Budget for the Month of '}}</small>
                        <input type="text" class="input-adjusted-budget-text form-control text-right input-sm" name="adj_budget_month" value="{{$wis_monthly['_budget']->adj_budget_month or 'Adjusted Over/Under Remaining Budget for the Month of '}}">
                    </div>
                </div>
                <div class="col-md-5 text-right digima-table-label">
                    <input type="text" class="change-val input-adjusted-budget-amount form-control text-right input-sm adj-budget-month-amount" name="adj_budget_month_amount" value=" {{$wis_monthly['_budget']->adj_budget_month_amount}}">
                </div>
            </div>
            <div class="row clearfix" style="margin-top: 5px">
                <div class="col-md-7 text-right">
                    <small>Less </small>
                </div>
                <div class="col-md-5">
                    <select class="1111 form-control select-item-offset input-sm pull-left item-select" name="" >
                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                        <option class="hidden" value="" />
                    </select>
                </div>
            </div> 
            <div class="row clearfix" style="margin-top: 5px">
                <div class="col-md-7 text-right"></div>
                <div class="col-md-5 text-right">
                    <div class="item-listing">
                        <table class="table table-condensed">
                            <tbody class="listing-offset-item">
                                @if(count($wis_monthly['_budgetline']) > 0)
                                    @foreach($wis_monthly['_budgetline'] as $budline)
                                    <tr class="offset-listing">
                                        <td class="text-center"><i class="remove-btn-offset cursor-pointer fa fa-times" style="color: red"></i></td>
                                        <td class="text-left">
                                            <input type="hidden" class="offset-input-itemid" name="budgetline_item_id[]">
                                            <input type="hidden" class="offset-input-itemprice" name="budgetline_item_amount[]">
                                            <span class="offset-itemname"></span></td>
                                        <td class="text-right"><span class="offset-itemprice"></span></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row clearfix" style="margin-top: 5px">
                <div class="col-md-7 text-right"></div>
                <div class="col-md-5 text-right digima-table-label">
                    <input type="text" class="change-val form-control text-right input-sm offset-item-total item-less-amount" name="total_item_less_amount" value="({{$wis_monthly['_budget']->total_item_less_amount}})">
                </div>
            </div>
            <hr>
            <div class="row clearfix hidden" style="margin-top: 5px">
                <div class="col-md-7 text-right digima-table-label">
                    <div class="col-md-12">
                        <!-- <small>Total Adjusted Over/Under (Remaining) Budget for the Month of </small> -->
                        <small class="hidden display-total-adjusted-text"> {{$wis_monthly['_budget']->total_budget_month or 'Total Adjusted Over/Under Remaining Budget for the Month of '}}</small>
                        <input type="text" class="input-total-adjusted-text form-control text-right input-sm" name="total_budget_month" value="{{$wis_monthly['_budget']->total_budget_month or 'Total Adjusted Over/Under Remaining Budget for the Month of '}}">
                    </div>
                </div>
                <div class="col-md-5 text-right digima-table-label">
                    <!-- <input type="text" class="form-control text-right input-sm total-adj-amount" name="total_budget_month_amount" value=""> -->
                    <input type="text" class="change-val input-total-adjusted-amount form-control text-right input-sm adj-budget-month-amount" name="total_budget_month_amount" value="{{$wis_monthly['_budget']->total_budget_month_amount}}">
                </div>
            </div>
        </div>
        @endif
    @endif
@endif