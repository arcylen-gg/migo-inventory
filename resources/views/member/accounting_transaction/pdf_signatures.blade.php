<table class="" {{$ctr = 0}} style="width: 100%">
    <tr style="padding: 30px">
        @if(isset($_signatories))
            @if(count($_signatories) > 0)
                @foreach($_signatories as $key => $signatory)
                <td style="width: 30%;padding: 30px">
                <table  class="text-center" {{$ctr = $key+1}} style="width: 100%;">
                    <tr><td style="width: 100%">{{$signatory->settings_value}}</td></tr>
                    <tr><td class="text-center"><div style="border-bottom: 1px solid #000;width: 90%"></div></td></tr>
                    <tr><td style="width: 100%"><strong> {{$signatory->settings_key}} </strong></td></tr>
                </table>
                @if($ctr % 3)
                </td>
                @else
                </tr>
                <tr>
                @endif
                </td>
                @endforeach
            @endif
        @endif
    </tr>
</table>