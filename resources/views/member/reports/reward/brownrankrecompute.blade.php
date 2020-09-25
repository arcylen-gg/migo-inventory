<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Re-Compute Brown Rank</h4>
</div>
<div class="modal-body clearfix">
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:0%">
        </div>
    </div>
    <div class="text-center"><b><span class="bilang">0</span> SLOT</b> out of <b>{{ $count }} SLOT(S)</b></div>

    <div class="text-center" style="margin-top: 20px;">
        <button class="btn btn-primary start-recompute">START RE-COMPUTE OF RANKING</button>
    </div>

    <div class="slot-container hidden">
        @foreach($_slot as $slot)
        <div class="slot" slot_id="{{ $slot->slot_id }}">{{ $slot->first_name }} {{ $slot->last_name }} ({{ $slot->slot_no }})</div>
        @endforeach
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    <button class="btn btn-primary btn-custom-primary" type="button">Submit</button>
</div>

<script type="text/javascript">

    var count = 0;
    var dynacount = 0;

    $(document).ready(function()
    {
        count = $(".slot").length;
        dynacount = $(".slot").length;


        $(".start-recompute").click(function()
        {
            compute();
        });
    });


    function compute()
    {
        $slot_id = $(".slot:first").attr("slot_id");
        $token = "{{ csrf_token() }}";



        $(".start-recompute").attr("disabled", "disabled");
        $(".start-recompute").text($(".slot:first").text());
        $(".slot:first").remove();

        $.ajax(
        {
            url:"/member/report/reward-brown-rank-compute",
            data: {'slot_id':$slot_id, '_token':$token},
            type:"post",
            success: function(data)
            {
                var dynacount = $(".slot").length;
                var current = ((count - dynacount) / count) * 100;

                $(".progress-bar").css("width", current + "%");
                $(".bilang").text(count - dynacount);
                compute();
            }
        });
    }
</script>