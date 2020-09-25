<form action="{{$action}}" method="post" class="global-submit">
    {{ csrf_field() }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">{{$process}} BROWN RANK</h4>
    <input type="hidden" name="rank_id" value="{{$brown_rank->rank_id or ''}}">
</div>
<div class="modal-body clearfix">
    <div class="row">
        <div class="clearfix modal-body"> 
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <label for="basic-input">Rank Name</label>
                        <input id="basic-input" value="{{$brown_rank->rank_name or ''}}" name="rank_name" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group">

                    <div class="col-md-6">
                        <label for="basic-input">Direct</label>
                        <input id="basic-input" value="{{$brown_rank->required_direct or ''}}" name="required_direct" class="form-control" placeholder="">
                    </div>

                </div>
                <div class="form-group">

                    <div class="col-md-6">
                        <label for="basic-input">Required Slots</label>
                        <input id="basic-input" value="{{$brown_rank->required_slot or ''}}" name="required_slot" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-6">
                        <label for="basic-input">Up to Level</label>
                        <select class="form-control" name="required_uptolevel">
                            @for($ctr=1; $ctr<=20; $ctr++)
                            <option {{isset($brown_rank) ? ($brown_rank->required_uptolevel == $ctr ? 'selected' : '') : ''}} value="{{$ctr}}">{{ ordinal($ctr) }} Level</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="basic-input">Builder Reward Percentage</label>
                        <input id="basic-input" value="{{$brown_rank->builder_reward_percentage or ''}}" name="builder_reward_percentage" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-6">
                        <label for="basic-input">Up to Level</label>
                        <select class="form-control" name="builder_uptolevel">
                            @for($ctr=1; $ctr<=20; $ctr++)
                            <option {{isset($brown_rank) ? ($brown_rank->builder_uptolevel == $ctr ? 'selected' : '') : ''}} value="{{$ctr}}">{{ ordinal($ctr) }} Level</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label for="basic-input">Leader Override Build Reward</label>
                        <input id="basic-input" value="{{$brown_rank->leader_override_build_reward or ''}}" class="form-control" name="leader_override_build_reward" placeholder="">
                    </div>
                    <div class="col-md-6">
                        <label for="basic-input">Up to Level</label>
                        <select class="form-control" name="leader_override_build_uptolevel">
                            <option {{isset($brown_rank) ? ($brown_rank->leader_override_build_uptolevel == 'unlimited' ? 'selected' : '') : ''}} value="unlimited">Unlimited</option>
                            @for($ctr=1; $ctr<=20; $ctr++)
                            <option {{isset($brown_rank) ? ($brown_rank->leader_override_build_uptolevel == $ctr ? 'selected' : '') : ''}} value="{{$ctr}}">{{ ordinal($ctr) }} Level</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label for="basic-input">Leader Override Direct Referral</label>
                        <input id="basic-input" value="{{$brown_rank->leader_override_direct_reward or ''}}" name="leader_override_direct_reward" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-6">
                        <label for="basic-input">Up to Level</label>
                        <select class="form-control" name="leader_override_direct_uptolevel">
                            <option {{isset($brown_rank) ? ($brown_rank->leader_override_direct_uptolevel == 'unlimited' ? 'selected' : '') : ''}} value="unlimited">Unlimited</option>
                            @for($ctr=1; $ctr<=20; $ctr++)
                            <option {{isset($brown_rank) ? ($brown_rank->leader_override_direct_uptolevel == $ctr ? 'selected' : '') : ''}} value="{{$ctr}}">{{ ordinal($ctr) }} Level</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    <button class="btn btn-primary btn-custom-primary" type="submit">Save Rank</button>
</div>
</form>