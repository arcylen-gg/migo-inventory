<table width="100%">
	<tr>
		<td>Greetings {{name_format_from_customer_info($customer)}} , <br></td>
	</tr>
	<tr>
		<tr>
			<td>
			<h4>You have successfully purchased a PhilTECH Discount Card from {{name_format_from_customer_info($customer_sponsor)}}! Please read important details below:</h4>
			<ul>
				<li>
					Date Issued:  {{$discount_card->discount_card_log_date_used}}
					
				</li>
				<li>
					Date of Expiry: {{$discount_card->discount_card_log_date_expired}}
				</li>
			</ul>
			</td>
		</tr>
	</tr>
	<tr>
		<td>
			<p>You may now enjoy a full year of Discount Privilege with every purchase on all products of the company by simply presenting your Discount Card during payment.</p>
		</td>
	</tr>
	<tr>
		<td><p>For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), philtechglobalmainoffice@gmail.com (Email), or you may visit us at our Main Office located at PhilTECH Bldg. (2nd Level), Gallera Road, Guiwan, Zamboanga City.</p></td>
	</tr>
	<tr>
		<td><p>Congratulations!</p></td>
	</tr>
	<tr>
		<td><p>Sincerely,</p></td>
	</tr>
	<tr>
		<td><p><b>The PhilTECH Admin Team</b></p></td>
	</tr>
</table>
