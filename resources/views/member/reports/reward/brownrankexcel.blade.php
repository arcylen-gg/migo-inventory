<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <thead>
            <tr>
                <th style="text-align: left; width: 20;">SLOT</th>
                <th style="text-align: left; width: 40;">CUSTOMER NAME</th>
                <th style="text-align: left; width: 30;">E-MAIL</th>
                <th style="text-align: left; width: 25;">CONTACT</th>
                <th style="text-align: left; width: 20;">CURRENT RANK</th>
                <th style="text-align: left; width: 40;">RANK STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($_slot as $slot)
            <tr>
                <td class="text-left">{{ $slot->slot_no }}</td>
                <td class="text-left">{{ $slot->first_name }} {{ $slot->last_name }}</td>
                <td class="text-left">{{ $slot->email }}</td>
                <td class="text-left">{{ ($slot->contact == "" ? $slot->customer_mobile : $slot->contact) }}</td>
                <td class="text-left">{{ $slot->brown_current_rank }}</td>
                <td class="text-left">{!! $slot->brown_next_rank_requirements !!} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</html>