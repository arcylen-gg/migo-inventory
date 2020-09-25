<!-- TUTORIAL GROUP - SUBJECT -->
<p>
   <h4 class="subtitle">D. SLOT CREATION COMPUTE</h4>
   <div class="gray-author">by Luke Glenn Jordan (PHP/Laravel)</div>
</p>
<p>
   <div>
      Dependencies:
      <div><pre>{{ 'use App\Globals\Mlm_compute;' }}</pre></div>
   </div>
</p>
<p>
   <div>After insertion of new slot. Get the slot id and call this function:</div> 
   <pre>Mlm_compute::entry($slot_id);</pre>
   <div>This function will compute all the active Active Slot creation computation plan in: <c>member/mlm/plan</c></div>
   <div>This will also insert the slot to the Placement Tree(if binary complan is activated) and Unilevel/Sponsor Tree.</div>
</p>