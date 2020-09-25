<div class="form-group" style="padding-left:20px ">
    <table style="width: 100%">
         @if(isset($_signatories))
            @if(count($_signatories) > 0)
                @foreach($_signatories as $key => $signatory)
                    @if($key == 0)
                    <tr style="padding: 30px"><td colspan="2">Prepared by:</td></tr>
                    <tr style="padding: 30px"><td colspan="2">&nbsp;</td></tr>
                    @elseif($key == 1)
                    <tr style="padding: 30px"><td colspan="2">Checked by:</td></tr>
                    <tr style="padding: 30px"><td colspan="2">&nbsp;</td></tr>
                    @elseif($key == 2)
                    <tr style="padding: 30px"><td colspan="2">Approved by:</td></tr>
                    <tr style="padding: 30px"><td colspan="2">&nbsp;</td></tr>
                    @endif
                    <tr style="padding: 30px;margin-bottom: 20px">
                        <td class="text-center" style="width: 50%">
                            <table  class="text-center" style="width: 100%;text-align:center">
                                <tr><td style="width: 100%"><strong>{{$signatory->settings_value}}</strong></td></tr>
                                <tr><td style="width: 100%"><i>{{$signatory->settings_key}} </i></td></tr>
                            </table>
                        </td>
                        <td style="width: 50%" class="text-center">
                            <div>Date : </div>
                        </td>
                    </tr>
                    <tr style="padding: 30px"><td colspan="2">&nbsp;</td></tr>
                    <tr style="padding: 30px"><td colspan="2">&nbsp;</td></tr>
                    <tr style="padding: 30px"><td colspan="2">&nbsp;</td></tr>
                @endforeach
            @endif
        @endif        
    </table>
</div>