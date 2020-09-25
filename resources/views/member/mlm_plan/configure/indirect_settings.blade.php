
    @if($membership_count != 0)
       @if($indirect_settings_count != 0)
           <td colspan="3">
               <form class="global-submit" method="post" action="/member/mlm/plan/indirect/edit/settings/addlevel/save" id="indirect_settings_form{{$membership->membership_id}}">
               {!! csrf_field() !!}
               <input type="hidden" name="membership_id" value="{{$membership->membership_id}}">
                   <div class="col-md-12">
                   
                   
                   <a data-toggle="tooltip" data-placement="left" class="pull-right" title="Tooltip on left" href="javascript:" onClick="save_form_indirect_settings({{$membership->membership_id}})">Save</a> 
                   <label>Membership</label>
                     <input type="text" value="{{$membership->membership_name}}" class="form-control" disabled="disabled">
                   </div>
                   <div class="col-md-12">
                      <label for="indirect_level">Number of Levels</label>
                       <input type="number" class="form-control" name="indirect_level" value="{{$indirect_settings_count}}" id="indirect_level{{$membership->membership_id}}" onChange="change_indirect_level({{$membership->membership_id}})">
                
                   </div>
                   <span class="append_level{{$membership->membership_id}}">
                       @foreach($indirect_settings as $key => $value)
                        <div class='col-md-12'>
                        <div class='col-md-4'>
                        <label for='level_indirect[]'>Level</label><input type='hidden' class='form-control' value='{{$value->indirect_seting_level}}' name='level_indirect[]'><input type='number' class='form-control' value='{{$value->indirect_seting_level}}' name='level_indirect[]' disabled='disabled'>
                        </div>
                        <div class='col-md-2'>
                        <label for='amount_indirect[]'>Amount</label><input type='number' class='form-control' value='{{$value->indirect_seting_value}}' name='amount_indirect[]'>
                        </div>
                        <div class='col-md-2'>
                        <label for='additional_points[]'>Additional Points</label><input type='number' class='form-control' value='{{$value->additional_points}}' name='additional_points[]'>
                        </div>
                        <div class='col-md-4'>
                        <label for='percentage_indirect[]'>Type</label>
                            <select class='form-control' name='percentage_indirect[]'>
                                <option value='0' {{$value->indirect_seting_percent == 0 ? "selected" : ""}}>Fixed Amount</option>
                                <option value='1' {{$value->indirect_seting_percent == 1 ? "selected" : ""}}>Percentage</option>
                            </select>
                        </div>
                        </div>
                        @endforeach 
                   </span>
                </form>
                
            </td> 
                 
       @else
       <td colspan="3">
           <form class="global-submit" method="post" action="/member/mlm/plan/indirect/edit/settings/addlevel/save" id="indirect_settings_form{{$membership->membership_id}}">
           {!! csrf_field() !!}
           <input type="hidden" name="membership_id" value="{{$membership->membership_id}}">
               <div class="col-md-12">
               {{$membership->membership_name}}
               <a data-toggle="tooltip" data-placement="left" class="pull-right" title="Tooltip on left" href="javascript:" onClick="save_form_indirect_settings({{$membership->membership_id}})">Save</a> 
               </div>
               <div class="col-md-12">
                  <label for="indirect_level">Number of Levels</label>
                   <input type="number" class="form-control" name="indirect_level" value="{{$indirect_settings_count}}" id="indirect_level{{$membership->membership_id}}" onChange="change_indirect_level({{$membership->membership_id}})">
            
               </div>
               <span class="append_level{{$membership->membership_id}}"></span>
            </form>
            
        </td>   
       @endif
   @else
       <center>Invalid Membership</center>
   @endif
   
   <script type="text/javascript">
        function save_form_indirect_settings(membership_id)
        {
            $('#indirect_settings_form' + membership_id).submit();
        }
       function change_indirect_level(membership_id)
       {
            var no_of_level = $('#indirect_level' + membership_id).val();
            append_level(membership_id, no_of_level);
       }
       function append_level(membership_id, no_of_level)
       {
            no_of_level = parseInt(no_of_level) + 1;
            var html_to_append = "";
            for (i = 1; i < no_of_level; i++) { 
                html_to_append += "<div class='col-md-12'>";
                html_to_append += "<div class='col-md-4'>";
                html_to_append += "<label for='level_indirect[]'>Level</label><input type='number' class='form-control' value='"+i+"' name='level_indirect[]' readonly>";
                html_to_append += "</div>";
                html_to_append += "<div class='col-md-2'>";
                html_to_append += "<label for='amount_indirect[]'>Amount</label><input type='number' class='form-control' value='0' name='amount_indirect[]'>";
                html_to_append += "</div>";
                html_to_append += "<div class='col-md-2'>";
                html_to_append += "<label for='additional_points[]'>Amount</label><input type='number' class='form-control' value='0' name='additional_points[]'>";
                html_to_append += "</div>";
                html_to_append += "<div class='col-md-4'>";
                html_to_append += "<label for='percentage_indirect[]'>Type</label><select class='form-control' name='percentage_indirect[]'><option value='0'>Fixed Amount</option><option value='1'>Percentage</option></select>";
                html_to_append += "</div>";
                html_to_append += "</div>";
            }
            $('.append_level' + membership_id).html(html_to_append);
       }
   </script>
