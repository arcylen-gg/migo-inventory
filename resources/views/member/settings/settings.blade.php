<!--
	Put your setting form here
	Set your data at SettingsController
	The class of div must contain class "setting" and id "name of the settings"
	Inside the div it must contain an input with settings_key and settings_value
-->

<!-- Curreny Settings -->
	<div class="settings" id="currency">
		Currency
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="currency">
		<select name="settings_value[]" class="form-control"> 
			@foreach($currency as $cur)
				<option value="{{$cur->iso}}" {{isset($_settings['currency']) ? ($_settings['currency'] == $cur->iso ? 'selected' : '') : ''}} >{{$cur->name}}</option>
			@endforeach 
		</select>
	</div>
<!-- End Currency Settings -->

<!-- Country Settings -->
	<div class="settings" id="country">
		Country
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="country">
		<select name="settings_value[]" class="form-control">
			@foreach($country as $cou)
				<option value="{{$cou->country_name}}" {{isset($_settings['country']) ? ($_settings['country'] == $cou->country_name ? 'selected' : '') : ''}}>{{$cou->country_name}}</option>
			@endforeach
		</select>
	</div>
<!-- End country Settings -->

<!-- Item Serial Settings -->
	<div class="settings" id="item_serial">
		Require Item Serial
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="item_serial">
		<select name="settings_value[]" class="form-control">
			<option value="enable" {{isset($_settings['item_serial']) ? ($_settings['item_serial'] == 'enable' ? 'selected' : '') : ''}}>ON</option>
			<option value="disable" {{isset($_settings['item_serial']) ? ($_settings['item_serial'] == 'disable' ? 'selected' : '') : ''}}>OFF</option>
		</select>
	</div>
<!-- Item Serial Settings -->

<!-- Bad Order Settings -->
	<hr>
	<center>Debit Memo</center>
	<div class="settings" id="debit_memo">
		Enable Debit Memo For Service Type transaction
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="debit_memo">
		<select name="settings_value[]" class="form-control">
			<option value="disable"  {{isset($_settings['debit_memo']) ? ($_settings['debit_memo'] == 'disable' ? 'selected' : '') : ''}}>OFF</option>
			<option value="enable"  {{isset($_settings['debit_memo']) ? ($_settings['debit_memo'] == 'enable' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<div class="settings" id="bad_order">
		Enable Bad Oder for Replacing Item
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="bad_order">
		<select name="settings_value[]" class="form-control">
			<option value="disable" {{isset($_settings['bad_order']) ? ($_settings['bad_order'] == 'disable' ? 'selected' : '') : ''}}>OFF</option>
			<option value="enable" {{isset($_settings['bad_order']) ? ($_settings['bad_order'] == 'enable' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
<!-- Bad Order Settings -->

	<hr>
	<center>MLM</center>
<!-- Item Serial Settings -->
	<div class="settings" id="use_product_as_membership">
		Use Product as Membership
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="use_product_as_membership">
		<select name="settings_value[]" class="form-control">
			<option value="0"  {{isset($_settings['use_product_as_membership']) ? ($_settings['use_product_as_membership'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1"  {{isset($_settings['use_product_as_membership']) ? ($_settings['use_product_as_membership'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<div class="settings" id="enable_consume_on_pending">
		Enable Consume Inventory on Pending Order
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="enable_consume_on_pending">
		<select name="settings_value[]" class="form-control">
			<option value="0"   {{isset($_settings['enable_consume_on_pending']) ? ($_settings['enable_consume_on_pending'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1"   {{isset($_settings['enable_consume_on_pending']) ? ($_settings['enable_consume_on_pending'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
<!-- Item Serial Settings -->


	<hr>
	<center>Ecommerce</center>
<!-- View invoice in Order -->
	<div class="settings" id="enable_view_invoice">
		View Invoice in Product Orders
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="enable_view_invoice">
		<select name="settings_value[]" class="form-control">
			<option value="0"  {{isset($_settings['enable_view_invoice']) ? ($_settings['enable_view_invoice'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1"  {{isset($_settings['enable_view_invoice']) ? ($_settings['enable_view_invoice'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<hr>
	<center>Accounting</center>
	<!-- Customer with Unit -->
	<div class="settings" id="accounting_module">
		Accounting Module
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="accounting_module">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['accounting_module']) ? ($_settings['accounting_module'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['accounting_module']) ? ($_settings['accounting_module'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<hr>
	<center>Accounting - Taylormade</center>
	<!-- Customer with Unit -->
	<div class="settings" id="customer_unit_receive_payment">
		Customer with Unit in Receive Payment
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="customer_unit_receive_payment">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['customer_unit_receive_payment']) ? ($_settings['customer_unit_receive_payment'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['customer_unit_receive_payment']) ? ($_settings['customer_unit_receive_payment'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<hr>
	<center>Custimization - Fieldmen</center>
	<div class="settings" id="monthly_budget">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="monthly_budget">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['monthly_budget']) ? ($_settings['monthly_budget'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['monthly_budget']) ? ($_settings['monthly_budget'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<center>Custimization - Abubots</center>
	<div class="settings" id="monthly_budget">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="out_of_stock_WIS_on">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['out_of_stock_WIS_on']) ? ($_settings['out_of_stock_WIS_on'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['out_of_stock_WIS_on']) ? ($_settings['out_of_stock_WIS_on'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>


	<center>Custimization - Migo</center>
	<div class="settings" id="monthly_budget">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="migo_customization">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['migo_customization']) ? ($_settings['migo_customization'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['migo_customization']) ? ($_settings['migo_customization'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>


	<hr>
	<center>Accounting - WIS/DR</center>
	<!-- Customer with Unit -->
	<div class="settings" id="customer_wis">
		Warehouse Issuance Slip / Delivery Receipt
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="customer_wis">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['customer_wis']) ? ($_settings['customer_wis'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['customer_wis']) ? ($_settings['customer_wis'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<center>Inventory</center>
	<!-- Customer with Unit -->
	<div class="settings" id="customer_wis">
		Allow Out of Stock
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="out_of_stock">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['out_of_stock']) ? ($_settings['out_of_stock'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['out_of_stock']) ? ($_settings['out_of_stock'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<center>Item Costing</center>
	<div class="settings" id="item_new_cost">
		Compute costing in terms of :
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="item_new_cost">
		<select name="settings_value[]" class="form-control">
			<option value="new_cost" {{isset($_settings['item_new_cost']) ? ($_settings['item_new_cost'] == 'new_cost' ? 'selected' : '') : ''}}>New Cost</option>
			<option value="last_in_first_out" {{isset($_settings['item_new_cost']) ? ($_settings['item_new_cost'] == 'last_in_first_out' ? 'selected' : '') : ''}}>Last in First out</option>
			<option value="first_in_first_out" {{isset($_settings['item_new_cost']) ? ($_settings['item_new_cost'] == 'first_in_first_out' ? 'selected' : '') : ''}}>First in First out</option>
			<option value="average_costing" {{isset($_settings['item_new_cost']) ? ($_settings['item_new_cost'] == 'average_costing' ? 'selected' : '') : ''}}>Average Costing</option>
		</select>
	</div>

	<center>Item range sales discount</center>
	<div class="settings" id="range_sales_discount">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="range_sales_discount">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['range_sales_discount']) ? ($_settings['range_sales_discount'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['range_sales_discount']) ? ($_settings['range_sales_discount'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<center>Sales Representative</center>
	<div class="settings" id="sales_representative">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="sales_representative">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['sales_representative']) ? ($_settings['sales_representative'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['sales_representative']) ? ($_settings['sales_representative'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	
	<center>Bank Interest</center>
	<div class="settings" id="bank_interest">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="bank_interest">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['bank_interest']) ? ($_settings['bank_interest'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['bank_interest']) ? ($_settings['bank_interest'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	
	<center>Per Warehouse Reorder Point</center>
	<div class="settings" id="monthly_budget">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="perwarehouse_reorder">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['perwarehouse_reorder']) ? ($_settings['perwarehouse_reorder'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['perwarehouse_reorder']) ? ($_settings['perwarehouse_reorder'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<center>Auto Print Reorder from other warehouse in Dashboard</center>
	<div class="settings" id="monthly_budget">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_load_reorder_print">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['auto_load_reorder_print']) ? ($_settings['auto_load_reorder_print'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['auto_load_reorder_print']) ? ($_settings['auto_load_reorder_print'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<center>Hours to set(24 Hr Format)</center>
	<div class="settings" id="auto_load_reorder_hour">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_load_reorder_hour">
		<input name="settings_value[]" class="form-control" value="{{isset($_settings['auto_load_reorder_hour']) ? $_settings['auto_load_reorder_hour'] : ''}}" />
		</select>
	</div>
	<center>Minutes to set</center>
	<div class="settings" id="auto_load_reorder_hour">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_load_reorder_min">
		<input name="settings_value[]" class="form-control" value="{{isset($_settings['auto_load_reorder_min']) ? $_settings['auto_load_reorder_min'] : ''}}" />
		</select>
	</div>
	
	<center>Notification</center>

	<div class="settings" id="customer_wis">
		Notify me on Notification bar
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="notification_bar">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['notification_bar']) ? ($_settings['notification_bar'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['notification_bar']) ? ($_settings['notification_bar'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<div class="settings" id="reorder_item">
		Popup Notification for Reorder Items
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="reorder_item">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['reorder_item']) ? ($_settings['reorder_item'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['reorder_item']) ? ($_settings['reorder_item'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>

	<hr>
	<center>Transaction</center>
	<div class="settings" id="transaction_number">
		Different Series of Transaction Number per branches
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="transaction_number">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['transaction_number']) ? ($_settings['transaction_number'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['transaction_number']) ? ($_settings['transaction_number'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<div class="settings" id="per_day_reset">
		Per Day Reset Transaction Number
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="per_day_reset">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['per_day_reset']) ? ($_settings['per_day_reset'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['per_day_reset']) ? ($_settings['per_day_reset'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<div class="settings" id="allow_transaction">
		Allow transaction show in different branches
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="allow_transaction">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['allow_transaction']) ? ($_settings['allow_transaction'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['allow_transaction']) ? ($_settings['allow_transaction'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<hr>
	<center>Bin Location</center>
	<div class="settings" id="enable_bin_location">
		Bin Location per item and per warehouse
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="enable_bin_location">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['enable_bin_location']) ? ($_settings['enable_bin_location'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['enable_bin_location']) ? ($_settings['enable_bin_location'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<hr>
	<center>Purchase Requisition</center>
	<div class="settings" id="purchase_requisition">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="purchase_requisition">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['purchase_requisition']) ? ($_settings['purchase_requisition'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['purchase_requisition']) ? ($_settings['purchase_requisition'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<hr>
	<center>Barcode</center>
	<div class="settings" id="enable_barcode">
		Enable barcode
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="enable_barcode">
		<select name="settings_value[]" class="form-control">
			<option value="1" {{isset($_settings['enable_barcode']) ? ($_settings['enable_barcode'] == '1' ? 'selected' : '') : ''}}>ON</option>
			<option value="0" {{isset($_settings['enable_barcode']) ? ($_settings['enable_barcode'] == '0' ? 'selected' : '') : ''}}>OFF</option>
		</select>
	</div>
	<center>Print Barcode</center>
	<div class="settings" id="enable_print_barcode">
		Enable Print barcode
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="enable_print_barcode">
		<select name="settings_value[]" class="form-control">
			<option value="1" {{isset($_settings['enable_print_barcode']) ? ($_settings['enable_print_barcode'] == '1' ? 'selected' : '') : ''}}>ON</option>
			<option value="0" {{isset($_settings['enable_print_barcode']) ? ($_settings['enable_print_barcode'] == '0' ? 'selected' : '') : ''}}>OFF</option>
		</select>
	</div>
	<hr>
	<div class="settings" id="allow_transaction">
		Equipment type in Category
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="equipment_category">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['equipment_category']) ? ($_settings['equipment_category'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['equipment_category']) ? ($_settings['equipment_category'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<div class="settings" id="allow_transaction">
		Customer Proposal Number
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="customer_proposal_number">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['customer_proposal_number']) ? ($_settings['customer_proposal_number'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['customer_proposal_number']) ? ($_settings['customer_proposal_number'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<hr>
	<div class="settings" id="allow_transaction">
		More Optimize WIS/WT
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="optimize_wiswt">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['optimize_wiswt']) ? ($_settings['optimize_wiswt'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['optimize_wiswt']) ? ($_settings['optimize_wiswt'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<hr>
	<center>Dashboard</center>
	<div class="settings" id="allow_transaction">
		Auto Load Dashboard
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_load_dashboard">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['auto_load_dashboard']) ? ($_settings['auto_load_dashboard'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['auto_load_dashboard']) ? ($_settings['auto_load_dashboard'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<!-- <hr>
	<div class="settings">
		VAT Computation
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="vat_computation">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['vat_computation']) ? ($_settings['vat_computation'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['vat_computation']) ? ($_settings['optimize_wiswt'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div> -->
	<hr>
	<center>Item Type</center>
	<br>
	<div class="settings" id="allow_transaction">
		
		<div class="form-group">
			<table class="table table-condensed table-bordered">
				<thead>
					<tr>
						<td></td>
						<td>Type</td>
					</tr>
				</thead>
				<tbody>
					@if(count($_type) > 0)
						@foreach($_type as $type)
						<tr>
							<td class="text-center {{$name = strtolower(str_replace(' ','_',$type->item_type_name))}}">
								<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
								<input type="checkbox" class="item-type-check" {{isset($_settings[$name]) ? ($_settings[$name] == 1 ? 'checked' :'') : '' }} data-value="{{$name}}" >
								<input type="hidden" name="settings_value[]" value=" {{isset($_settings[$name]) ? $_settings[$name] : 0 }}" class="{{$name}}-input">
							</td>
							<td><input type="hidden" name="settings_key[]" value="{{$name}}">{{$type->item_type_name}}</td>
						</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<hr>
	<center>Transaction Printables</center>
	<div class="settings">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="project_name">
		<br>
		<label class="radio-inline"><input type="radio" {{isset($_settings['project_name']) ? ($_settings['project_name'] == 'default' ? 'checked' : '') : 'checked'}} name="settings_value[]" value="default"> Default</label>
		<label class="radio-inline"><input type="radio" {{isset($_settings['project_name']) ? ($_settings['project_name'] == 'fieldmen' ? 'checked' : '') : ''}} name="settings_value[]" value="fieldmen"> Fieldmen</label>
		<label class="radio-inline"><input type="radio" {{isset($_settings['project_name']) ? ($_settings['project_name'] == 'migo' ? 'checked' : '') : ''}} name="settings_value[]" value="migo"> Migo</label>
		<label class="radio-inline"><input type="radio" {{isset($_settings['project_name']) ? ($_settings['project_name'] == 'woa' ? 'checked' : '') : ''}} name="settings_value[]" value="woa"> WOA</label>
		</div>
	</div>
	<br>
	<div class="settings">
		Auto change Sales and Cost Price
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_change_sales_price">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['auto_change_sales_price']) ? ($_settings['auto_change_sales_price'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['auto_change_sales_price']) ? ($_settings['auto_change_sales_price'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<div class="settings">
		Terms to be used
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="terms_to_be_used">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['terms_to_be_used']) ? ($_settings['terms_to_be_used'] == '0' ? 'selected' : '') : ''}}>BUNDLE</option>
			<option value="1" {{isset($_settings['terms_to_be_used']) ? ($_settings['terms_to_be_used'] == '1' ? 'selected' : '') : ''}}>SET</option>
		</select>
	</div>
	<div class="settings">
		Auto Received Warehouse Transfer
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_received_wt">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['auto_received_wt']) ? ($_settings['auto_received_wt'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['auto_received_wt']) ? ($_settings['auto_received_wt'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<div class="settings">
		Auto Post Transactions
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_post_transaction">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['auto_post_transaction']) ? ($_settings['auto_post_transaction'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['auto_post_transaction']) ? ($_settings['auto_post_transaction'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div>
	<!-- <div class="settings">
		Auto Undeposit Account
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="auto_undeposit_acc">
		<select name="settings_value[]" class="form-control">
			<option value="0" {{isset($_settings['auto_undeposit_acc']) ? ($_settings['auto_undeposit_acc'] == '0' ? 'selected' : '') : ''}}>OFF</option>
			<option value="1" {{isset($_settings['auto_undeposit_acc']) ? ($_settings['auto_undeposit_acc'] == '1' ? 'selected' : '') : ''}}>ON</option>
		</select>
	</div> -->