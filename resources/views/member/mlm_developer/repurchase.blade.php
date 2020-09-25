
<form class="global-submit form-horizontal test_repurchase_form" role="form" action="/member/mlm/developer/repurchase" method="post">
    {{ csrf_field() }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">CREATE MLM SLOT FOR TESTING <button class="btn btn-primary pull-right all_zero_btn" type="button" style="margin-right:10px;">All Zero</button></h4>
    </div>
    <div class="modal-body clearfix">
         <div class="form-group">
            <div class="col-md-12">            
                <label>PURCHASE BY (SLOT NO.)</label>
                <input name="slot_no" type="text" class="form-control" placeholder="RANDOM (IF EMPTY)" >
            </div>
         </div>

        <div class="form-group">
            <div class="col-md-12">            
                <label>PRODUCT PRICE</label>
                <input name="price" type="text" class="form-control" placeholder="100 (IF EMPTY)">
            </div>
        </div>
        

        @if(isset($unilevel))
            @if($unilevel == 1)
            <div class="form-group">
                <div class="col-md-12">            
                    <label>GROUP PV</label>
                    <input name="UNILEVEL" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif
        
        @if(isset($repurchase_points))
            @if($repurchase_points == 1)
            <div class="form-group">
                <div class="col-md-12">            
                    <label>PV</label>
                    <input name="REPURCHASE_POINTS" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif
        
        @if(isset($repurchase_cashback))
            @if($repurchase_cashback == 1)
            <div class="form-group">
                <div class="col-md-12">            
                    <label>REPURCHASE CASHBACK</label>
                    <input name="REPURCHASE_CASHBACK" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div> 

            <div class="form-group">
                <div class="col-md-12">            
                    <label>REPURCHASE CASHBACK POINTS</label>
                    <input name="REPURCHASE_CASHBACK_POINTS" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>          

            <div class="form-group">
                <div class="col-md-12">            
                    <label>RANK REPURCHASE CASHBACK</label>
                    <input name="RANK_REPURCHASE_CASHBACK" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif
        
        @if(isset($unilevel_repurchase_points))
            @if($unilevel_repurchase_points == 1)
            <div class="form-group">
                <div class="col-md-12">            
                    <label>UNILEVEL REPURCHASE POINTS</label>
                    <input name="UNILEVEL_REPURCHASE_POINTS" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif
        
        @if(isset($discount_card_repurchase))
            @if($discount_card_repurchase == 1)
            <div class="form-group">
                <div class="col-md-12">            
                    <label>DISCOUNT CARD REPURCHASE</label>
                    <input name="DISCOUNT_CARD_REPURCHASE" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif
        
        @if(isset($stairstep))
            @if($stairstep == 1)
            <div class="form-group">
                <div class="col-md-6">            
                    <label>STAIRSTEP POINTS</label>
                    <input name="STAIRSTEP" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
                <div class="col-md-6">            
                    <label>STAIRSTEP GROUP POINTS</label> 
                    <input name="STAIRSTEP_GROUP" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif

        @if(isset($rank))
            @if($rank == 1)
            <div class="form-group">
                <div class="col-md-6">            
                    <label>RANK POINTS</label>
                    <input name="RANK" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
                <div class="col-md-6">            
                    <label>RANK GROUP POINTS</label> 
                    <input name="RANK_GROUP" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif

        @if(isset($binary_repurchase))
            @if($binary_repurchase == 1)
            <div class="form-group">
                <div class="col-md-12">            
                    <label>BINARY REPURCHASE</label>
                    <input name="BINARY_REPURCHASE" type="text" class="form-control" placeholder="100 (IF EMPTY)">
                </div>
            </div>
            @endif
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Create Re-Purchase</button>
    </div>
</form>

<script type="text/javascript">
    function repurchase_submit_done(data)
    {
        if(data.status == "error_message")
        {
            toastr.error(data.error_message);
        }
        else if(data.status == "success")
        {
            toastr.success('Done!');
            $("#global_modal").modal("hide");
            mlm_developer.action_load_data();
        }
    }

    $(".all_zero_btn").click(function()
    {
        $("form.test_repurchase_form :input").each(function()
        {
            if($(this).attr("name") != "slot_no" && $(this).attr("name") != "_token")
            {
                $(this).val(0);
            }
        });
    });
</script>