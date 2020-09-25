<body>
	@foreach($_item as $item)
		<label class="hidden" {{$ctr = 0}}></label>
	 	@if($item->item_barcode != '')
	 		<table style="width: 100%;page-break-after:always;" >
	 			<tr style="page-break-after:always;">
				 	@for ($i = 1; $i <= 24; $i++)
		 				<td style="width: 30%">
							<table style="border: solid 1px #000; width: 250px;padding: 10px">
								<tr style="text-align: center">
									<td>{{$item->item_name}}</td>
								</tr>
								<tr style="text-align: center">
									<td>
										<img style="height: 30px;width: 200px;object-fit: contain;" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->item_barcode, 'EAN13')}}" alt="barcode"/>
									</td>
								</tr>
								<tr style="text-align: center">
									<td>{{$item->item_barcode}}</td>
								</tr>
							</table>
			                @if($i % 3)
			                </td>
			                @else
			                </tr>
			                <tr>
			                @endif
		 				</td>
				    @endfor
	 			</tr>
	 		</table>
		@endif
	@endforeach
</body>
<style type="text/css">
	.page 
	{
		page-break-after:always;
		position: relative;
	}
</style>