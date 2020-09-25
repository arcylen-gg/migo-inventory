<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">These item reached reorder point!</h4>
</div>
<form class="global-submit" method="POST" action="{{$action or ''}}">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="warehouse_id" value="{{ $warehouse_id }}">
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group text-right">
            <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-custom-primary">Order Item</button>
        </div>
        <div class="form-group text-center">
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center"><input type="checkbox" class="check-all-item" name=""></th>
                        <th class="text-center">ITEM NAME</th>
                        <th class="text-center">REORDER POINT</th>
                        <th class="text-center">REMAINING QTY</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($_item) > 0)
                        @foreach($_item as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="td-item new-checkbox" name="item_id[{{$item['item_id']}}]" value="{{$item['item_id']}}">
                            </td>
                            <td>{{$item['item_name']}}</td>
                            <td>
                                <input type="hidden" name="item_reorder[{{$item['item_id']}}]" value="{{$item['item_reorder']}}">
                                {{$item['item_reorder']}}
                            </td>
                            <td>
                                <input type="hidden" name="item_qty[{{$item['item_id']}}]" value="{{$item['item_qty']}}">
                                <label>{{$item['item_qty']}}</label>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4">NO ITEM</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-custom-primary">Order Item</button>
</div>
</form>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('body').on('click','.check-all-item', function()
        {
            $('input:checkbox.new-checkbox').not(this).prop('checked', this.checked);         
        });
        $('body').on('click','.td-item', function()
        {
            $(this).prop('checked', this.checked);
        });
    });
    function success_reorder(data)
    {
        if(data.status == 'success')
        {
            toastr.success(data.status_message);
            location.href = data.status_redirect;
        }
    }
</script>