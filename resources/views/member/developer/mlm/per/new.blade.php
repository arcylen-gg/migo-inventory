<p>
   <h4 class="subtitle">F. CREATE NEW COMPLAN</h4>
   <div class="gray-author">by Luke Glenn (PHP/LARAVEL)</div>
</p>
<p>
	<div>Dependencies: </div>
<pre>
use App\Models\Tbl_mlm_plan;		
</pre>
</p>
<p>
	<div>Inserting of new complan</div>
	<pre>
// The shop id of the complan	
$insert['shop_id'] = $shop_id;

// The complan code
$insert['marketing_plan_code'] = "UNILEVEL_REPURCHASE_POINTS";

// The complan name
$insert['marketing_plan_name'] = "Unilevel Repurchase Points";

// Where the plan will be triggered 
// "Slot Creation" will be triggered upon creation of slot;
// "Product Repurchase" will be triggered upon repurchase of product;
$insert['marketing_plan_trigger'] = "Product Repurchase";

// The label of the complan. This is what the MLM Member will see.
$insert['marketing_plan_label'] = "Unilevel Repurchase Points";

// 0 if disable and 1 if enabled
$insert['marketing_plan_enable'] = 0;

// Insert the complan
Tbl_mlm_plan::insert($insert);		
	</pre>
</p>
<p>
	<div>Upon insertion of the complan it will automatically be added in the Complan List (member/mlm/plan)</div>
</p>



