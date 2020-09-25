
<style type="text/css">
    .chosen-container
    {
        width: 100% !important;
    }
</style>
<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">ITEM</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container" >
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                <h3>{{$item_details->item_name or 'No item Selected'}}</h3>
            </div>
        </div>
        <div class="row clearfix draggable-container">
            <div class="table-responsive">
            <!-- <div class="col-md-12">
                <input type="text" class="form-control" id="search_txt" placeholder="Search Serial Number...">
            </div> -->
             <div class="col-md-12">
                    <div class="row clearfix draggable-container ilr-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table" id="serial_table">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;"> SIR # </th>
                                            <th style="width: 15px;" class="text-center">Sales Agent</th>
                                            <th style="width: 15px;" class="text-center">Plate Number</th>
                                            <th style="width: 15px;" class="text-center">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($_sir) > 0)
                                            @foreach($_sir as $sir)
                                                <tr>
                                                    <td>{{$sir->sir_id}}</td>
                                                    <td>{{$sir->first_name." ".$sir->middle_name." ".$sir->last_name}}</td>
                                                    <td>{{$sir->plate_number}}</td>
                                                    <td>{{$sir->per_agent_qty}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="4">No SIR in this Item.</td>
                                        </tr>
                                        @endif
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