<!-- TUTORIAL GROUP - SUBJECT -->
<p>
   <h4 class="subtitle">A. SLOT CREATION POPUP</h4>
   <div class="gray-author">by Luke Glenn Jordan (HTML)</div>
</p>
<p style="color: red">
	Note: Don't show this at the MLM member's area.
</p>
<pre>{{ '<button  class="panel-buttons btn btn-default pull-right popup" link="/member/mlm/slot/add" size="lg">Add New Slot</button>' }}</pre>


<p>
   <div>To use the <i>create Slot form</i> for new slot. You need to use the <span class="text-success text-bold">.popup</span> class and use a specific link which is <span class="text-success text-bold">/member/mlm/slot/add</span>. This form will adjust defending on the settings in marketing plan. E.G. Binary is disabled, slot placement will not be asked.</div>
</p>
<p>
	<label><span><small style="color: green">Sample</small></span></label><br>
	<button  class="panel-buttons btn btn-default popup" link="/member/mlm/slot/add" size="lg">Add New Slot</button>
</p>
<p>Callback:
</p>
<p>
   <pre>slot_created(slot_data);</pre>
   This triggers after saving the slot information in the database. The parameter <c>slot_data</c> will return the slot information.
   <p>
     <div><c>Slot information</c> are as follows:</div>
     <ul>
        <li>slot_no (int) <i> Slot No of the slot.</i></li>
        <li>shop_id (int) <i> Shop Id of the slot.</i></li>
        <li>slot_owner (int) <i>Customer Id of the slot.</i></li>
        <li>slot_created_date (datetime) <i>Date of create of the slot</i></li>
        <li>slot_membership (int) <i>Membership Id of the the slot.</i></li>
        <li>slot_status (string) <i>Status of the slot (PS/CD/FS)</i></li>
        <li>slot_sponsor (int) <i>Slot Id of the Sponsor</i></li>
     </ul>
  </p>
</p>