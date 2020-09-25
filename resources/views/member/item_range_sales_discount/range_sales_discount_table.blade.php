<div class="form-group">
    <div class="col-md-12" style="padding: 30px;">
        <!-- START CONTENT -->
        <div class="row clearfix draggable-container">
            <div class="table-responsive " id="item-tbl">
                <div class="col-sm-12">
                    <table class="digima-table">
                        <thead >
                            <tr>
                                <th style="width: 15px;">#</th>
                                <th style="width: 300px;">Item Name</th>
                                <th style="width: 120px;">Current Sales Price</th>
                                <th style="width: 70px;">Qty</th>
                                <th style="width: 120px;">New Price /pc</th>
                                <th style="width: 30px;"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable tbody-item">
                            @if($previous_item_range->count() != 0)
                            @foreach($previous_item_range as $key => $previous_item_range_data)
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-right">1</td>
                                <td>
                                    <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]">
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "",'item_id' => $previous_item_range_data->item_id])
                                        <option class="hidden" value="" />
                                        </select>
                                    </td>
                                    <td>
                                        <input class="text-right txt-rate compute" readonly="true" type="text" name="item_rate[]" value="{{number_format($previous_item_range_data->item_price,2)}}"/>
                                    </td>
                                    <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{number_format($previous_item_range_data->range_qty)}}"/></td>
                                    <td><input class="text-center number-input txt-new-price compute" type="text" name="item_new_price[]" value="{{number_format($previous_item_range_data->range_new_price_per_piece,2)}}" /></td>
                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                </tr>
                                @endforeach
                                @endif
                                <tr class="tr-draggable">
                                    <td class="invoice-number-td text-right">1</td>
                                    <td>
                                        <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                            @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                            <option class="hidden" value="" />
                                            </select>
                                        </td>
                                        <td>
                                            <input class="text-right txt-rate compute" readonly="true" type="text" name="item_rate[]"/>
                                        </td>
                                        <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                        <td><input class="text-center number-input txt-new-price compute" type="text" name="item_new_price[]" value="" /></td>
                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                    </tr>
                                    <tr class="tr-draggable">
                                        <td class="invoice-number-td text-right">2</td>
                                        <td>
                                            <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                <option class="hidden" value="" />
                                                </select>
                                                
                                            </td>
                                            <td>
                                                <input class="text-right txt-rate compute" readonly="true" type="text" name="item_rate[]"/>
                                            </td>
                                            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                            <td><input class="text-center number-input txt-new-price compute" type="text" name="item_new_price[]" value="" /></td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END CONTENT -->
                </div>
            </div>

<div class="pull-right" style="padding: 10px">{!! $previous_item_range->render() !!}</div>