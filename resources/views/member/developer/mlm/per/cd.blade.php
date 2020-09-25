
<p>
   <h4> G. COMMISSION DEDUCTIBLE SLOT</h4>
   <div class="gray-author">by Luke Glenn Jordan (PHP/LARAVEL)</div>
</p>
<p>
<div>Dependency</div>
<pre>
use App\Globals\Mlm_compute;
use App\Globals\Mlm_complan_manager_cd;   
</pre>
<pre>
$slot_id = 1;
$slot_info = Mlm_compute::get_slot_info($slot_id);   
</pre>   
</p>
<p>
   <div>Put slot into CD status</div>
<pre>
Mlm_complan_manager_cd::enter_cd($slot_info)
</pre>
   <div>This function will deduct the membership amount to the slot's wallet</div>
</p>
<p>
   <div>Check if Slot already meet the requirements to be a PS</div>
<pre>
Mlm_complan_manager_cd::graduate_check($slot_info)
</pre>  
   <div>If the slot already has the required amount to be a PS. The slot will be notified that he/she has graduated CD status. The slot status will be updated to PS</div> 
</p>