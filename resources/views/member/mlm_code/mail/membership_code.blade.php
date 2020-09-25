
<table width="100%">
	<tr>
		<td>Greetings {{name_format_from_customer_info($invoice)}}, <br></td>
	</tr>
	<tr>
		<tr>
			<td>
			<h4>You have successfully registered:</h4>
			<ul>
			@foreach($membership_code_invoice_id as $key => $value)
			<li>
				<p> {{$value->membership_name}} with a package of {{$value->membership_package_name}}. Your membership code is {{$value->membership_activation_code}} and membership pin {{$value->membership_code_id}}. </p>
				<a href="{{$_SERVER['SERVER_NAME']}}/mlm/membership_active_code/{{Crypt::encrypt($value->membership_code_id)}}">Click here to activate the code.</a>
			</li>
			@endforeach
			</ul>
			</td>
		</tr>
	</tr>
	<tr>
		<td>
			<p>As a PhilTECH VIP, you are entitled to Lifetime Privileges and Benefits exclusively designed for our VIPs. Experience shopping convenience like never before with our E-Commerce System. Enjoy Discounts and Earn Cashback + Rewards Points with every purchase on all products of the company. Just present your PhilTECH VIP Card during payment and all these exciting benefits will be yours.</p>
		</td>
	</tr>
	<tr>
		<td>
			<p>For inquiries, you may reach us at (062) 310-2256 or at (0917)-542-2614. You can also email us at philtechglobalmainoffice@gmail.com. Our friendly customer service personnel will be more than happy to assist you.</p>
		</td>
	</tr>
	<tr>
		<td>Congratulations!</td>
	</tr>
	<tr>
		<td>Sincerely,</td>
	</tr>
	<tr>
		<td><b>The PhilTECH Admin Team</b></td>
	</tr>
</table>