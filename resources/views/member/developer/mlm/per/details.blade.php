<!-- TUTORIAL GROUP - SUBJECT -->
<p>
   <h4 class="subtitle">C. Get Slot Details</h4>
   <div class="gray-author">by Luke Glenn Jordan (PHP/Laravel)</div>
</p>
<p>
   <div>
      Dependencies:
      <div><pre>{{ 'use App\Globals\Mlm_compute;' }}</pre></div>
   </div>
</p>
<p>
<pre>
$slot_id = 1;
$slot_info = Mlm_compute::get_slot_info($slot_id);
</pre>
</p>
<p>

     <div>Returned information are as follows:</div>
     <ul>
        <li>tbl_mlm_slot (table) <i> All data in the table where slot_id is equals to the called slot_id</i></li>
        <li>tbl_membership (table)</li>
        <li>tbl_membership_points (table)</li>
        <li>tbl_membership_code (table)</li>
        <li>tbl_customer (table)</li>
     </ul>
  </p>
</p>