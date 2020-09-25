
  
  <thead>
     <tr>
         <th style="text-align: center;width: 5%;">ACTION</th>
         <th style="text-align: center;width: 25%;">COMPANY</th>
         <th style="text-align: center;width: 25%;">RECIPIENT</th>
         <th style="text-align: center;width: 25%;">POSITION</th>   
         <th style="display:none;">Email</th>   
     </tr>
  </thead>  
  <tbody>
     @foreach($_recipient as $recipients)
     <tr>
         <input type="hidden" id="recipient_id" name="recipient_id[]" value="{{$recipients->recipient_id}}">
         <td style="text-align: center;"><input type="checkbox" class="recipient_checkbox" name="checkbox" value="{{$recipients->recipient_id}}" ></td>
         <td style="text-align: center;">{{$recipients->company_name}}</td>
         <td class="rec_name_{{$recipients->recipient_id}}" style="text-align: center;">{{$recipients->name}}</td>
         <td style="text-align: center;">{{$recipients->position}}</td>
         <td class="rec_email_{{$recipients->recipient_id}}" style="display:none;">{{$recipients->research_email_address}}</td>
     </tr>
     @endforeach
  </tbody>   
<script>
  $(document).ready(function()
  {
      var $checkboxes = $('#choose_recipient_form td input[type="checkbox"]'); 
      $checkboxes.change(function()
      {
          var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
          $('#Chosen_total').text(countCheckedCheckboxes);
      });
  });
</script>



