@if(isset($serial))
	@if($serial)
		<td>
			<textarea class="txt-serial-number" name="serial_number[]"></textarea>
		</td>
	@endif
<!-- <td>
	<a class="popup" link="/member/input/serial_number" size="md"><i class="fa fa-upload"></i></a>
</td> -->
@endif