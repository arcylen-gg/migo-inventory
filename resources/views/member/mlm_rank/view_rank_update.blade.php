    <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th><center>Customer Name</center></th>
          <th><center>Slot No.</center></th>
          <th><center>Current Rank</center></th>
          <th><center>Personal STAIR-PV</center></th>
          <th><center>Group STAIR-PV</center></th>
          <th><center>New Rank</center></th>
        </tr>
      </thead>
      <tbody>
        @foreach($_rank_slot as $rank_slot)
          <tr>
            <td><center>{{$rank_slot->first_name}} {{$rank_slot->middle_name}} {{$rank_slot->last_name}}</center></td>
            <td><center><a href="#" class='underline'><span>{{$rank_slot->slot_no}}</span></a></center></td>
            <td><center>{{$rank_slot->old_rank_name}}</center></td>
            <td><center>{{$rank_slot->rank_personal_pv}}</center></td>
            <td><center>{{$rank_slot->rank_group_pv}}</center></td>
            <td><center>{{$rank_slot->new_rank_name}}</center></td>
          </tr>
        @endforeach
      </tbody>
    </table>