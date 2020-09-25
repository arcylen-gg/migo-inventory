<form class="form-add-account global-submit" action="/member/accounting/chart_of_account/add" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Account</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="col-md-3 pull-right">
                <input type="text" class="form-control" name="account_number" id="account_number" value="" >
            </div>
            <label  class="pull-right" for="account_number" >#</label>
        </div>
        <div class="row inside-content">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="account_type">Account Type</label>
                    <select class="form-control" name="account_type_id" id="account_type">
                        @foreach($_account_type as $account_type)
                            <option value="{{$account_type->chart_type_id}}" has-balance="{{$account_type->has_open_balance}}" data-desc="{{$account_type->chart_type_description}}">{{$account_type->chart_type_name}}</option>
                        @endforeach
                    </select>
                    
                </div>
                <div class="form-group">
                    <div id="account_type_description" disabled="disabled" style="overflow:auto; overflow-x:hidden; max-height:190px"></div>
                </div>
                <!--<div class="form-group">-->
                <!--    <input type="text" class="form-control" placeholder="Note:">-->
                <!--</div>-->
            </div>
            <div  class="col-md-6">
                <div class="form-group">
                    <label for="account_name">Account Name</label>
                    <input type="text" class="form-control" name="account_name" id="account_name" value="" required>
                </div>
                <div class="form-group">
                    <label for="account_description">Account Description</label>
                    <input type="text" class="form-control" name="account_description" id="account_description" value="">
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label><input type="checkbox" id="is_sub_account" name="is_sub_account"> Is sub-account</label>
                    </div>
                    <select class="form-control" name="account_parent_id" id="account_parent_id" disabled="disabled" required>
                        <option value="">Enter parent account</option>
                        @foreach($_account as $sub_account)
                            <option class="sub-account " sub-type-id="{{$sub_account->account_type_id}}" value="{{$sub_account->account_id}}">{{$sub_account->account_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row balance-container" style="">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_open_balance">Balance</label>
                            <input type="number" value="0" class="form-control" name="account_open_balance" id="account_open_balance">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_open_balance_date">As of</label>
                            <input type="text" class="datepicker form-control input-sm" name="account_open_balance_date" id="account_open_balance_date">
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary btn-add-account" type="submit">Add account</button>
    </div>
</form>
<script type="text/javascript" src="/assets/member/js/chart_account.js"></script>