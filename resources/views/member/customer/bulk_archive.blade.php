<form class="form-to-submit-add" action="/member/customer/bulk_archive" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">Customer Bulk Archive</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <div class="col-md-6">
                    <h3 style="margin-top: 10px">Customers</h3>
                </div>
            </div>
            <div class="row clearfix draggable-container warehouse-adjust-container">
                <div class="table-responsive">
                    <div class="col-sm-12">
                        <table class="digima-table">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">Customer Name</th>
                                    <th style="width: 10px"></th>
                                </tr>
                            </thead>
                            <tbody class="draggable tbody-item">
                                <tr class="tr-draggable">
                                    <td class="">
                                        <select class="form-control count-select select-item input-sm customer-droplist" id="select_item" name="customer_id[]" select_id="1" data-placeholder="Select a Item">
                                            @foreach($_customer as $customer)
                                            <option value="{{ $customer->customer_id }}">{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center cursor-pointer remove-tr"><i onClick="$(this).parent().parent().remove()" class="fa fa-trash-o" aria-hidden="true"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Bulk Archive</button>
    </div>
</form>
<script type="text/javascript">
var append = '<tr class="tr-draggable">'+
                '<td class="">'+
                    '<select class="form-control count-select select-item input-sm customer-droplist" id="select_item" name="customer_id[]" select_id="1" data-placeholder="Select a Item">'+
                        @foreach($_customer as $customer)
                        '<option value="{{ $customer->customer_id }}">{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>'+
                        @endforeach
                    '</select>'+
                '</td>'+
                '<td class="text-center cursor-pointer remove-tr"><i onClick="$(this).parent().parent().remove()" class="fa fa-trash-o " aria-hidden="true"></i></td>'+
            '</tr>';
</script>
<script type="text/javascript" src="/assets/member/js/bulk_archive.js"></script>