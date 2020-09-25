<tr id="{{$tr_id}}">
    <td class="text-left">
        <span><a href="javascript:" class="popup" link="{{$link.$data->ecommerce_banking_id}}">{{$data->ecommerce_banking_name}}</a>
        <a href="javascript:" class="pull-right popup" link="/member/ecommerce/settings/archive_warning_bank/{{Crypt::encrypt($bank_a->ecommerce_banking_id)}}"><i class="fa fa-times"></i></a></span>
    </td>
</tr>