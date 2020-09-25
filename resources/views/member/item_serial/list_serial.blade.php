
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">ITEM SERIAL NUMBER</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container" >
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                {{$item_name->item_name}}
            </div>
        </div>
        <div class="row clearfix draggable-container">
            <div class="table-responsive">
            <div class="col-md-12">
                <input type="text" class="form-control" id="search_txt" placeholder="Search Serial Number...">
            </div>
             <div class="col-md-12">
                    <div class="row clearfix draggable-container ilr-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table" id="serial_table">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;"> # </th>
                                            <th style="width: 15px;" class="text-center">Serial Number</th>
                                            <th style="width: 15px;" class="text-center"></th>
                                            <th style="width: 15px;" class="text-center">Sold</th>
                                            <th style="width: 15px;" class="text-center">Pull out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($_item_serial as $serial)
                                        <tr>
                                            <td>{{$serial->serial_id}}</td>
                                            <td class="text-center">
                                                <span class="lbl-serial-{{$serial->serial_id}}">{{$serial->serial_number}} </span>
                                                <input type="text" class="form-control txt-serial-{{$serial->serial_id}} hidden" value="{{$serial->serial_number}}" name="serial_edit">
                                            </td>
                                            <td class="text-center">
                                                <i class="fa fa-edit fa-2x btn-edit-{{$serial->serial_id}}" onclick="view_edit({{$serial->serial_id}})"></i>
                                                <i class="fa fa-floppy-o fa-2x btn-save-{{$serial->serial_id}} hidden" onclick="save_edit({{$serial->serial_id}})"></i>
                                            </td>
                                            <td class="text-center"><input type="checkbox" disabled name="sold_serial" {{$serial->item_consumed == 1 && $serial->sold == 1 ? 'checked' : ''}}></td>
                                            <td class="text-center"><input type="checkbox" disabled name="pull_out_seiral" {{$serial->item_consumed == 1 && $serial->sold == 0 ? 'checked' : ''}}></td>
                                        </tr>
                                    @endforeach
                                    </tbody>                                   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div>    
    </div>
</div>

<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
</div>
<script type="text/javascript">
    function view_edit(id)
    {
        $(".lbl-serial-"+id).addClass("hidden");
        $(".txt-serial-"+id).removeClass("hidden");
        $(".btn-save-"+id).removeClass("hidden");
        $(".btn-edit-"+id).addClass("hidden");
    }
    function save_edit(id)
    {
        $(".modal-loader").removeClass("hidden");
        var serial = $(".txt-serial-"+id).val();
        $.ajax({
            url : "/member/item/save_serial",
            type : "get",
            data : {id : id , serial : $(".txt-serial-"+id).val()},
            success : function(data)
            {
                data = $.parseJSON(data);
                if(data.status == "success")
                {
                    toastr.success("Success");
                    $(".lbl-serial-"+id).html(serial);
                    $(".lbl-serial-"+id).removeClass("hidden");
                    $(".btn-edit-"+id).removeClass("hidden");
                    $(".btn-save-"+id).addClass("hidden");
                    $(".txt-serial-"+id).addClass("hidden");
                    $(".modal-loader").addClass("hidden");
                }
                else if(data.status == "error")
                {
                    toastr.warning(data.status_message);
                    $(".modal-loader").addClass("hidden");
                }
            }
        })
    }

    $('#search_txt').keyup(function()
    {
        searchTable($(this).val());
    });
    function searchTable(inputVal)
    {
        var table = $('#serial_table');
        table.find('tr').each(function(index, row)
        {
            var allCells = $(row).find('td');
            if(allCells.length > 0)
            {
                var found = false;
                allCells.each(function(index, td)
                {
                    var regExp = new RegExp(inputVal, 'i');
                    if(regExp.test($(td).text()))
                    {
                        found = true;
                        return false;
                    }
                });
                if(found == true)$(row).show();else $(row).hide();
            }
        });
    }
</script>