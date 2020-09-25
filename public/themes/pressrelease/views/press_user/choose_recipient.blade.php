<div class="popup-choose">
   <!-- Modal content-->
   <div class="modal-content" id="recipient-modal" name="recipient-modal">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <div class="title-container"> Found Results:  <span class="result-container" style="font-weight:bold;font-size:18px">{{$total_query}}</span>
         </div>
      </div>
      <div class="modal-body">
         <div class="row pull-right">
            <div class="right-container">
               <button  type="button" id="select_all" name="select_all" class="btn btn-success" >Select All Found</button>
               <button  type="button" id="unselect_all" name="unselect_all" class="btn btn-success" >Unselect All Found</button>
            </div>
         </div>
         <div class="row clearfix">
            <div class="search-container">
               <input placeholder="Search" type="text"  name="search_key" id="search_key">
               <button  type="button" name="search_button" id="search_button" class="btn btn-success" >Search</button>
            </div>
            <div class="" style="padding:10px 10px 10px 10px;">
               <label style="font-size:18px">Chosen Recipients: </label> <span class="result-container" style="font-weight:bold;font-size:18px" id="Chosen_total">0</span>
            </div>
            <div class="col-md-12">
               <form action="/pressuser/pressrelease/recipient/done" method="POST" id="choose_recipient_form">
                  <div class="left-container"">
                     <table  class="display table table-bordered" style="background-color: #FFFFFF;" id="showHere_table">
                        <thead style="background-color: white;">
                           <tr>
                              <th style="text-align: center;width: 5%"">ACTION</th>
                              <th style="text-align: center;width: 25%">COMPANY</th>
                              <th style="text-align: center;width: 25%">RECIPIENT</th>
                              <th style="text-align: center;width: 25%">POSITION</th>   
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
                     </table>
                  </div>
                  <div class="row pull-right done-container">
                     <button type="button" id="recipient_button" name="recipient_button" class="btn btn-success">Done</button>
                  </div> 
               </form>  
            </div>
         </div>
      </div>
      <div class="modal-footer">      
      </div>
   </div>
</div>
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/choose_recipient.css">

<script  src="/assets/js/choose_recipient_release.js"></script>

