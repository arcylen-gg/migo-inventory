<form class="global-submit" role="form" action="/member/mlm/developer/modify_slot?slot_id={{ $slot_info->slot_id }}" method="post">
    {{ csrf_field() }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">FORCE EDIT SLOT DETAILS</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Slot No.</label>
                            <input disabled type="text" class="form-control" value="{{ $slot_info->slot_no }}">
                        </div>
                    </div>

                    @if($sponsor_info != null)
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Sponsor</label>
                            <input type="text" class="form-control" name="sponsor" value="{{ $sponsor_info->slot_no }}">
                        </div>
                    </div>
                    @endif

                    @if($placement_info != null)
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Placement</label>
                            <input type="text" name="placement" class="form-control" value="{{ $placement_info->slot_no }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Position</label>
                            <select name="position" class="form-control">
                                <option value="left" {{ $slot_info->slot_position == 'left' ? 'selected' : '' }}>LEFT</option>
                                <option value="right" {{ $slot_info->slot_position == 'right' ? 'selected' : '' }}>RIGHT</option>
                            </select>
                        </div>
                    </div>    
                    @endif


                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Membership</label>
                            <select name="membership_id" class="form-control">
                                @foreach($_membership as $membership)
                                <option {{ $slot_info->slot_membership == $membership->membership_id ? 'selected' : '' }} value="{{ $membership->membership_id }}">{{ $membership->membership_name }}</option>
                                @endforeach
                                <option value="delete_slot">DELETE SLOT</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Developer Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Update Slot</button>
    </div>
</form>

<script type="text/javascript">
    function modify_slot_success(data)
    {
        data.element.modal("hide");
        mlm_developer.action_load_data();
    }
</script>