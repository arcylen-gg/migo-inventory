<form action="/member/mlm/gcmaintenance/process-output" method="get">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title"><i class="fa fa-money"></i> PROCESS GC MAINTENANCE</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Wallet Amount of affected by GC Maintenance</label>
                            <input type="text" class="form-control" name="amount" value="5000">
                        </div>
                        <div class="col-md-12">
                            <label for="basic-input">Maintenance Amount</label>
                            <input type="text" class="form-control" name="maintenance" value="1500">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        <button type="submit" class="btn btn-primary btn-custom-primary"  type="button"><i class="fa fa-check"></i> Process GC Maintenance</button>
    </div>
</form>