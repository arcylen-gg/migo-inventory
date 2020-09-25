@extends('member.layout')
@section('content')
<link rel="stylesheet" type="text/css" href="/email_assets/email_css/create_email.css">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-envelope"></i>
            <h1>
                <span class="page-title">Press Release Email System</span>
            </h1>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-background">
    <header class="header_email">
    <div class="panel-body form-horizontal tab_header">
        <div class="form-group">
           <ul class="nav nav-pills">
        <li class=""><a href="/member/page/press_release_email/create_press_release"><big class="big">Create</big><small class="small"> New Release</small></a></li>
        <li class="choose active"><a href="/member/page/press_release_email/choose_recipient_press_release"><big class="big">Choose</big><small class="small"> recipients</small></a></li>
        <li><a href="/member/page/press_release_email/view_send_email_press_release"><big class="big">Send</big><small class="small"> Release </small></a></li>
      </ul>     
    </div>
    </div>
    </header>
    <input type="hidden"  class="_token1" id="_token1" value="{{csrf_token()}}"/>
                                      
  <form name="myform" method="GET" action="/member/page/press_release_email/search_recipient_press_release" id="#pass_data">
    <h4 style="margin-left: 10px;">Search By:</h4>

    <!-- <div class="form-group row">
    <label  class="label-name">Country</label>
    <div class="">
      <input type="text" class="form-control input col-md-6" id="country" placeholder="country" name="country">
    </div>
      </div> -->
  <div class="form-group row">
  <label  class="label-name">Country</label>
  <br>
  <div class ="select_container">
  <select class="names" id="country" multiple data-placeholder="choose country" >
  @foreach($_recipient_country as $recipient_country)
  <option value="{{$recipient_country->country}}">{{$recipient_country->country}}</option>
  @endforeach
</select>
</div>
  </div>
  <div class="form-group row">
    <label  class="label-name">Title of Journalist</label>
    <div class ="select_container">
    <select class="names" multiple data-placeholder="choose title of Journalist" id="title_of_journalist" >
    @foreach($_title_of_journalist as $title_of_journalist)
    <option value="{{$title_of_journalist->title_of_journalist}}">{{$title_of_journalist->title_of_journalist}}</option>
    @endforeach
    </select>
  </div>
  </div>
  <div class="form-group row">
    <label  class="label-name">Industry Type</label>
    <div class ="select_container">
    <select class="names" multiple data-placeholder="choose Industry Type" id="industry_type">
    @foreach($_type_of_industry as $industry_type)
    <option value="{{$industry_type->industry_type}}">{{$industry_type->industry_type}}</option>
    @endforeach
    </select>
  </div>
  </div>
  <div class="form-group chosen_recipient">
   <!--  <label for="inputPassword" class="label-choose-recipient">Chosen Recipient</label> -->
    <div class="">
      <input type="hidden" class="form-control col-md-6 input_chose_recipient col-md-6" id="inputPassword" placeholder="Email Address" name="recipient_to">
    </div>
</div>
  <div class="form-group row">
    <div class="">
      <button type="button" class="form-control inputsubmit btn btn-primary search" id="inputsubmit" name="search">Search</button>
    </div>
  </div>
</form>
<button class="form-control btn-primary con" type="button" >Continue</button>
<br>
<br>
<br>
</div>

<!-- Modal -->
  <div class="modal fade" id="RecipientModal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="row recipient_container col-md-6">
          <div class="recipient_container2">
            <h3 class="text-center recipient_text">Recipient Lists</h3>
            <input type="hidden"  class="_token1" id="_token1" value="{{csrf_token()}}"/>
            <div class="well box" style="max-height: ;: 300px;overflow: auto;">
              <button type="button" class="check-all btn btn-primary" onClick="selectall(this)">Check All</button>
                <ul class="list-group check_list" id="vc">
                  @foreach($_recipient_list as $recipient_list)
                  <li  class="list-group-item tocheck" name="foo" value="{{$recipient_list->research_email_address}}"> 
                    <input type = "checkbox" class="to_check" id="check">
                    {{$recipient_list->company_name}}{{$recipient_list->name}}{{$recipient_list->position}}{{$recipient_list->industry_type}}
                </li>
                  @endforeach
                </ul>
            </div>
        </div>
    </div>
       
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>
</div>
  
<script src="/email_assets/js/create_press_release.js"></script>
<script src="/email_assets/js/chosen.jquery.js"></script>
<script src="/email_assets/js/chosen.jquery.min.js"></script>
<script>
    $(".input_chose_email").click(function() {
        $('#email_databaseModal').modal('show');
            
    });
</script>
<!-- <script>
    function dosubmit()
{
  document.myform.action = "member.email_system.view_send_email_press_release" +
  document.myform.recipient_to.value;
  window.event.returnValue = true;
}
</script> -->
<script>
  $(document).ready(function(e) {
    $('.names').chosen({
    width:"40.7%"
    });
    
  });

  $('#select_all_country').click(function(){
    alert('123');
    $('#country').prop('selected', true); // Selects all options
});
</script>
<script>
    $('.con').click(function(event) {
    // var recipient_value = $('.input_chose_recipient').val();
    // var recipient_link = "/member/page/press_release_email/view_send_email_press_release?sent_email="+recipient_value;

    // window.location.href = recipient_link;

   var myArr = [];

                         $('#vc li').each(function (i) {

                           if($(this).children().is(":checked") == true){
                              myArr.push($(this).attr('value'));
                           
                           }else{
                            
                           }

                        })
                         

                      $.ajax({
                     type: "GET",
                     url: "/member/page/press_release_email/view_send_email_press_release/sent_email",
                     data: {myArr:myArr},
                     dataType:"json",
                     success: function(data){
                                               // var recipient_value = myArr;
                         var recipient_link = "/member/page/press_release_email/view_send_email_press_release";
                          window.location.href = recipient_link;
                     }  
                });



    });
</script>

<!-- <script>

  $('select').chosen({width: "300px"});

$('.chosen-toggle').each(function(index) {
console.log(index);
    $(this).on('click', function(){
    console.log($(this).parent().find('option').text());
         $(this).parent().find('option').prop('selected', $(this).hasClass('select')).parent().trigger('chosen:updated');
    });
});
</script> -->
@endsection

