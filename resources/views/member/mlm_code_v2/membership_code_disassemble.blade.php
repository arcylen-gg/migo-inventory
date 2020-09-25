<form action="/member/mlm/code2/disassemble" method="post" class="global-submit">
    {{ csrf_field() }}
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title"><i class="fa fa-yelp"></i> DISASSEMBLE MEMBERSHIP CODE</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <input type="text" class="form-control" id='search_txt' name="search_txt" placeholder="Search Keyword"/>
                    </div>
                    <div class="form-group">
                    	<table  id="item_table" class="table table-bordered table-striped table-condensed">
                    		<thead style="text-transform: uppercase">
                                <tr>
                                	<th class="text-center"></th>
                                    <th class="text-center" width="120px">Pin No.</th>
                                    <th class="text-center" width="120px">Activation</th>
                                    <th class="text-center" width="150px">Membership</th>
                                    <th class="text-center" width="150px">Membership Kit</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($_assembled_item_kit as $item)
	                                <tr>
	                                	<td class="text-center">
	                                		<div class="checkbox">
	                                			<input type="checkbox" name="record_log_id[]" value="{{$item->record_log_id}}">
	                                		</div>
	                                	</td>
	                                    <td class="text-center">{{$item->mlm_pin}}</td>
	                                    <td class="text-center">{{$item->mlm_activation}}</td>
	                                    <td class="text-center">{{$item->membership_name}}</td>
	                                    <td class="text-center">{{$item->item_name}}</td>
	                                </tr>                                
                                @endforeach
                            </tbody>
                    	</table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary assemble-code-submit" type="submit"><i class="fa fa-yelp"></i> Disassemble Code</button>
    </div>
</form>
<script type="text/javascript" >
    $('#search_txt').keyup(function()
    {
        searchTable($(this).val());
        if($(this).val() == "")
        {            
           searchTable(category);
        }
    });
     function searchTable(inputVal)
    {
        var table = $('#item_table');
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