
<p>
   <h4> H. MEMBERSHIP PRODUCT DISCOUNT</h4>
   <div class="gray-author">by Luke Glenn Jordan (PHP/LARAVEL)</div>
</p>
<p>
<div>Dependency</div>
<pre>
use App\Globals\Mlm_discount;
</pre> 
</p>
<p>
	<div><b>Get all discount of item per membership (All Membership)</b></div>
<pre>

// $data_type  can be null or 'json'. If null it will return a php array if json it will return json encode array
$discounted_item = Mlm_discount::get_discount_all_membership($shop_id, $item_id, $data_type = null)	
</pre>	
	<div>Returned information are as follows:</div>
	<ul>
		<li>status (string) success or error</li>
		<li>message (string) returns the error message</li>
		<li>discount (double) returns the discount of product</li>
	</ul>
</p>
<p>
	<div><b>Get all discount of item single membership</b></div>
<pre>

// $data_type  can be null or 'json'. If null it will return a php array if json it will return json encode array
$discounted_item = Mlm_discount::get_discount_single($shop_id, $item_id, $membership_id)	
</pre>	
	<div>Returned information are as follows:</div>
	<ul>
		<li>status (string) success or error</li>
		<li>message (string) returns the error message</li>
		<li>discount (double) returns the discount of product</li>
	</ul>
</p>