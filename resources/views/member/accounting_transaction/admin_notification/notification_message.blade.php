<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">You have notification!</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group text-center">
            <h4>
                {!! $_noti->notification_description !!}
            </h4>
        </div>
        <div class="form-group text-center">
            @if($_noti->is_popup == 0)
            <h4><a href="/member/transaction/{{$_noti->opposite_transaction}}">Click here to process</a></h4>
            @else
            <h4><a class="popup" size="md" link="/member/transaction/{{$_noti->opposite_transaction}}">Click here to process</a></h4>
            @endif           
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
</div>
<script type="text/javascript">
    function success_confirm(data)
    {
        if(data.status == 'success')
        {
            toastr.success('Success');
            location.reload();
        }
    }
</script>