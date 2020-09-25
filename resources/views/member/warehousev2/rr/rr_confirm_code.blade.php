<form class="global-submit" method="post" action="/member/transaction/receiving_report/input-code">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">Input Code</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            <div class="form-group">
            	<label>Receive From : </label>
                <select required class="form-control select-warehouse" name="warehouse_id">
                    @foreach($_warehouse as $warehouse)
                        <option value="{{$warehouse->warehouse_id}}">{{$warehouse->warehouse_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Receive Code : </label>
                <input required type="text" class="form-control" name="receiver_code">
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" >Start Receiving</button>
    </div>
</form>

<script type="text/javascript">
    $('.select-warehouse').globalDropList({
        hasPopup : 'false',
        width    : '100%'
    })
</script>