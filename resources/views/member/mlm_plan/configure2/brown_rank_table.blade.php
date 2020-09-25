<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center">Order</th>
            <th class="text-center">Rank Name</th>
            <th class="text-center">Required Slots</th>
            <th class="text-center" width="300px">BUILDER REWARD</th>
            <th class="text-center" width="350px">LEADER REWARD (OVERRIDING)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_brown_rank) > 0)
        @foreach($_brown_rank as $rank)
        <tr>
            <td class="text-center">{{$rank->rank_id}}</td>
            <td class="text-center">{{$rank->rank_name}}</td>
            @if($rank->required_slot)
            <td class="text-center">
                <b>{{$rank->required_direct}}</b> DIRECT and <b>{{$rank->required_slot}}</b> SLOTS UP TO <b>{{strtoupper(ordinal($rank->required_uptolevel))}} LEVEL</b>
            </td>
            @elseif($rank->required_direct)
            <td class="text-center">
            <b>{{$rank->required_direct}}</b> DIRECT
            </td>
            @else
            <td class="text-center">NO REQUIREMENTS</td>
            @endif
            @if($rank->builder_reward_percentage)
            <td class="text-center">
                <b>{{$rank->builder_reward_percentage}}%</b> OF GROUP PURCHASE UP TO <b>{{strtoupper(ordinal($rank->builder_uptolevel))}} LEVEL</b>
            </td>
            @else
            <td class="text-center">NO GROUP PURCHASE</td>
            @endif
            <td class="text-center">
                @if($rank->leader_override_build_reward || $rank->leader_override_direct_reward)
                @if($rank->leader_override_build_reward)
                <b>{{$rank->leader_override_build_reward}}%</b>  OF GROUP BUILDER REWARD UP TO <b>{{strtoupper( is_numeric($rank->leader_override_build_uptolevel) ? ordinal($rank->leader_override_build_uptolevel) : 'UNLIMITED')}} LEVEL</b><br>
                @endif
                @if($rank->leader_override_direct_reward)
                <b>{{$rank->leader_override_direct_reward}}%</b> OF GROUP DIRECT REFERRAL UP TO <b>{{strtoupper( is_numeric($rank->leader_override_direct_uptolevel) ? ordinal($rank->leader_override_direct_uptolevel) : 'UNLIMITED')}} LEVEL</b>
                @endif
                @else
                NO PASSUP
                @endif
            </td>
            <td class="text-center"><a class="text-center popup" size='md' link='/member/mlm/plan/brown_rank/add_rank?id={{$rank->rank_id}}'>MODIFY</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="6" class="text-center"> NO RANK YET</td>
        </tr>
        @endif
        <!--  <tr>
            <td class="text-center">1</td>
            <td class="text-center">Dealer</td>
            <td class="text-center">NO REQUIREMENTS</td>
            <td class="text-center">NO GROUP PURCHASE</td>
            <td class="text-center">NO PASSUP</td>
            <td class="text-center"><a class="text-center" href="">MODIFY</td>
        </tr>
        <tr>
            <td class="text-center">2</td>
            <td class="text-center">Builder</td>
            <td class="text-center">25 SLOTS UP TO 5TH LEVEL</td>
            <td class="text-center">3% OF GROUP PURCHASE UP TO 5TH LEVEL</td>
            <td class="text-center">NO PASSUP</td>
            <td class="text-center"><a class="text-center" href="">MODIFY</td>
        </tr>
        <tr>
            <td class="text-center">3</td>
            <td class="text-center">Leader</td>
            <td class="text-center"><b>50</b> SLOTS UP TO <b>5TH LEVEL</b></td>
            <td class="text-center"><b>3%</b> OF GROUP PURCHASE UP TO <b>5TH LEVEL</b></td>
            <td class="text-center">
                <div><b>10%</b> OF GROUP BUILDER REWARD UP TO <b>5TH LEVEL</b></div>
                <div><b>10%</b> OF GROUP DIRECT REFERRAL UP TO <b>5TH LEVEL</b></div>
            </td>
            <td class="text-center"><a class="text-center" href="">MODIFY</td>
        </tr> -->
    </tbody>
</table>