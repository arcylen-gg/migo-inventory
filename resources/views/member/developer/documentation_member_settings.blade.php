<div class="settings" id="currency">
    Currency
    <input type="hidden" name="settings_key" value="currency">
    <select name="settings_value" class="form-control"> 
       (@)foreach($currency as $cur)
          <option value="{$cur->iso}">{$cur->name}</option>
       (@)endforeach
    </select>
 </div>