
<p>
   <h4> E. SPECIFIC COMPUTATION PLAN</h4>
   <div class="gray-author">by Luke Glenn Jordan (PHP/LARAVEL)</div>
</p>
<p>
   <div>DEPENDENCIES:</div>
   <ul>
      <li>
         <pre>{{ 'use App\Globals\Mlm_complan_manager;'}}<br />Mlm_compute::get_slot_info($slot_id);
         </pre>
      </li>
   </ul>
</p>
<p>
   <div>To compute the newly created <i>Slot</i> with a specific complan </div>
</p>
<p>
   <pre>
// The Complan Code.  You can get this at <c>/member/mlm/plan</c>
$plan_code = 'DIRECT';

// Get the slot details;
$slot_id = 1;
$slot_details = Mlm_compute::get_slot_info($slot_id);

// Change the code to lower case
$plan_code = strtolower($plan_code);

// Compute the specific plan
Mlm_complan_manager::$plan_code($slot_details);

// or
Mlm_complan_manager::direct($slot_details);
   </pre>
</p>