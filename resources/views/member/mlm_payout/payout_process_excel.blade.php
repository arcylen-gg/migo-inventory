<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <thead>
            <tr>
                <th style="text-align: left; width: 20;">PAYOUT DATE</th>
                <th style="text-align: left; width: 20;">SLOT NO</th>
                <th style="text-align: left; width: 20;">METHOD</th>
                <th style="text-align: left; width: 20;">CUSTOMER</th>
                @if($method == "eon")
                    <th style="text-align: left; width: 30;">EON ACCOUNT NAME</th>
                    <th style="text-align: left; width: 30;">EON ACCOUNT NUMBER</th>
                    <th style="text-align: left; width: 30;">EON CARD NUMBER</th>
                @endif
                @if($method == "palawan_express")
                    <th style="text-align: left; width: 30;">RECEIVER'S NAME</th>
                    <th style="text-align: left; width: 30;">RECEIVER'S CONTACT NUMBER</th>
                @endif
                @if($method == "coinsph")
                    <th style="text-align: left; width: 30;">WALLET ADDRESS</th>
                @endif
                @if($method == "bank")
                    <th style="text-align: left; width: 30;">BANK NAME</th>
                    <th style="text-align: left; width: 30;">ACCOUNT NAME</th>
                    <th style="text-align: left; width: 30;">ACCOUNT TYPE</th>
                    <th style="text-align: left; width: 30;">ACCOUNT NUMBER</th>
                @endif
                <th style="text-align: right; width: 20;">PAYOUT AMOUNT</th>
                <th style="text-align: right; width: 20;">SERVICE CHARGE</th>
                <th style="text-align: right; width: 20;">OTHER CHARGE</th>
                <th style="text-align: right; width: 20;">TAX</th>
                <th style="text-align: right; width: 20;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($_slot as $slot)
            <tr>
                <td>{{ date("m/d/Y") }}</td>
                <td>{{ $slot->slot_no }}</td>
                <td>{{ strtoupper($slot->customer_payout_method) }}</td>
                <td>{{ $slot->first_name }} {{ $slot->last_name }}</td>
                @if($method == "eon")
                    <td>{{ $slot->slot_eon }}</td>
                    <td>{{ $slot->slot_eon_account_no }}</td>
                    <td>{{ "'" . $slot->slot_eon_card_no . "'" }}</td>
                @endif
                @if($method == "palawan_express")
                    <td>{{$slot->remittance_fname .' '.$slot->remittance_mname.' '.$slot->remittance_lname}}</td>
                    <td>{{$slot->remittance_contact_number}}</td>
                @endif
                @if($method == "coinsph")
                    <td>{{$slot->wallet_address}}</td>
                @endif
                @if($method == "bank")
                    <td>{{ $slot->payout_bank_name }}</td>
                    <td>{{ $slot->bank_account_name }}</td>
                    <td>{{ $slot->bank_account_type }}</td>
                    <td>{{ str_replace("-", "", $slot->bank_account_number) }}</td>
                @endif
                <td>{{ $slot->real_net }}</td>
                <td>{{ $slot->real_service }}</td>
                <td>{{ $slot->real_other }}</td>
                <td>{{ $slot->real_tax }}</td>
                <td>{{ $slot->real_encash }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</html>