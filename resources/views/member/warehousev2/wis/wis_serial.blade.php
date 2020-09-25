
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Barcode/Serials</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
    <div class="panel-body form-horizontal">
        <div class="form-group text-center">
        	<h4><b>{{$item->item_name or ''}}</b></h4>
        	<h4>{{$item->item_sku or ''}}</h4>
        </div>
        <div class="form-group">
        	<table class="table table-bordered text-center">
        		<thead>
	        		<tr>
	        			<th class="text-center">#</th>
	        			<th class="text-center">Barcode/Serial</th>
	        		</tr>
	        	</thead>
	        	<tbody {{$ctr = 1}}>
	        		@foreach($_serial as $key => $serial)
	        		<tr>
	        			<td class="text-center">{{$ctr++}}</td>
	        			<td class="text-center">{{$serial}}</td>
	        		</tr>
	        		@endforeach
	        	</tbody>
        	</table>
        </div>
    </div>
</div>
<div class="modal-footer" >
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
</div>