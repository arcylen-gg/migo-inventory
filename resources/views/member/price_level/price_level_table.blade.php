 <table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center">#</th>
            <th class="text-center" width="300px">PRICE LEVEL NAME</th>
            <th class="text-center" >PRICE LEVEL TYPE</th>
            <th class="text-center" ></th>
        </tr>
    </thead>
    <tbody>
    	@if(count($_list) > 0)
        	@foreach($_list as $key => $list)
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{ucwords($list->price_level_name)}}</td>
                <td class="text-center">{{ucwords(str_replace('-',' ',$list->price_level_type))}}</td>
                <td class="text-center">
                   	<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                      	<li><a href="javascript:" class="popup" link="/member/item/price_level/add?id={{$list->price_level_id}}" size="lg">Modify</a></li>
                      </ul>
                    </div>
                </td>
            </tr>
            @endforeach
    	@else
    	<tr><td colspan="4" class="text-center">NO PRICE LEVEL YET</td></tr>
    	@endif
    </tbody>
</table>
<div class="pull-right">{!! $_list->render() !!}</div>
