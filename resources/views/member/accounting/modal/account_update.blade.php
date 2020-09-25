
<form class="form-add-account global-submit" action="/member/accounting/chart_of_account/update/{{$account_info->account_id}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Account</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="col-md-3 pull-right">
                <input type="text" class="form-control" name="account_number" id="account_number" value="{{$account_info->account_number}}">
            </div>
            <label  class="pull-right" for="account_number">#</label>
        </div>
        <div class="row inside-content">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="account_type">Account Type</label>
                    <select class="form-control" name="account_type_id" id="account_type">
                        @foreach($_account_type as $account_type)
                            <option value="{{$account_type->chart_type_id}}" has-balance="{{$account_type->has_open_balance}}" {{$account_info->account_type_id == $account_type->chart_type_id ? 'selected' : ''}}>{{$account_type->chart_type_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="7" disabled="disabled"></textarea>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Note:">
                </div>
            </div>
            <div  class="col-md-6">
                <div class="form-group">
                    <label for="account_name">Acount Name</label>
                    <input type="text" class="form-control" name="account_name" id="account_name" value="{{$account_info->account_name}}" required>
                </div>
                <div class="form-group">
                    <label for="account_description">Acount Description</label>
                    <input type="text" class="form-control" name="account_description" id="account_description" value="{{$account_info->account_description}}">
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label><input type="checkbox" id="is_sub_account" name="is_sub_account" {{$account_info->account_parent_id != null ? 'checked' : ''}}> Is sub-account</label>
                    </div>
                    <select class="form-control input-sm" name="account_parent_id" id="account_parent_id" disabled="disabled">
                        <option value="">Enter parent account</option>
                        @foreach($_account as $sub_account)
                            <option class="sub-account " sub-type-id="{{$sub_account->account_type_id}}" value="{{$sub_account->account_id}}" {{$account_info->account_parent_id == $sub_account->account_id ? 'selected' : ''}}>{{$sub_account->account_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-edit-account" type="submit">Update account</button>
    </div>
</form>
<script type="text/javascript" src="/assets/member/js/chart_account.js"></script>