<form class="global-submit" role="form" action="/member/customer/viewlead/{{ $id }}" method="post">
    {{ csrf_field() }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">VIEW LEAD</h4>
    </div>
    <div class="modal-body clearfix">
        @if($lead)
            LEAD OF THIS CUSTOMER IS <b>{{ strtoupper($lead->first_name) }} {{ strtoupper($lead->last_name) }} ({{ $lead->slot_no }})</b>
        @else
            THIS CUSTOMER DOESN'T HAVE ANY CUSTOMER LEAD
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        @if($lead)
            <button class="btn btn-primary btn-custom-primary" type="submit">Clear Lead</button>
        @endif
    </div>
</form>

<script type="text/javascript">
    function clear_lead_success(data)
    {
        toastr.success("Successfully Cleared");
        data.element.modal("hide");
    }
</script>