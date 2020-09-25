<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        @if(count($tokens)>0)
        <tr>
            <th class="text-center">Token ID</th>
            <th class="text-center">Token Name</th>
            <th class="text-center" width="100px"></th>
        </tr>
        @else
        <tr>
        	<th class="text-center">No Data</th>
        </tr>
        @endif
    </thead>
    <tbody>
        @foreach($tokens as $token)
        <tr id="{{ $token->token_id }}">
        	<td class="text-center">{{$token->token_id}}</td>
            <td class="text-center">{{$token->token_name}}</td>
            <td class="text-center">
	            <div class="btn-group">
	                <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                Action <span class="caret"></span>
	                </button>
	                <ul class="dropdown-menu dropdown-menu-custom">
	                    <li>
	                    	@if($token->archived == 0)
	                        <a class="token-archive" href="javascript:">Archive</a>
	                        @else
	                        <a class="token-archive" href="javascript:">Restore</a>
	                        @endif
	                    </li>
	                    <li>
	                        <a class="token-modify" href="javascript:"><i></i> Modify</a>
	                    </li>
	                </ul>
	            </div>
            </td>    
        </tr>
        @endforeach
    </tbody>
</table>