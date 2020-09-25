
<p>
   <h4> I. SESSION (MLM MEMBER'S AREA)</h4>
   <div class="gray-author">by Luke Glenn Jordan (PHP/LARAVEL)</div>
</p>
<p>
<div>Dependency</div>
<pre>
use App\Globals\Mlm_member;

// Controller extended to MLM 
// E.G. MlmDashboardController extends Mlm
</pre>
<p>
	<div><b>Add to session</b></div>
	<div>This function will add the member to session</div>
	<div>If the member has slot it will also add the first slot to session</div>
<pre>
Mlm_member::add_to_session($shop_id, $customer_id)
</pre>
</p>
<p>
	<div><b>Add to session with specific slot</b></div>
	<div>This function will add the member and slot to session</div>
<pre>
Mlm_member::add_to_session_edit($shop_id, $customer_id, $slot_id);	
</pre>	
</p>
<p>
	<div><b>Get currenct session (Customer Id)</b></div>
<pre>
$customer_id = Self::$customer_id;
</pre>	
</p>
<p>
	<div><b>Get currenct session (Customer Information)</b></div>
<pre>
$customer_info = Self::$customer_info;	
</pre>	
</p>
<p>
	<div><b>Get currenct session (Shop Id)</b></div>
<pre>
$shop_id = Self::$shop_id;	
</pre>	
</p>
<p>
	<div><b>Get currenct session (Shop Information)</b></div>
<pre>
$shop_info = Self::$shop_info;	
</pre>	
</p>
<p>
	<div><b>Get currenct session (Slot Information)</b></div>
<pre>
$slot_now = Self::$slot_now;	
</pre>	
</p>
<p>
	<div><b>Get currenct session (Slot Id)</b></div>
<pre>
$slot_id = Self::$slot_id;	
</pre>	
</p>








