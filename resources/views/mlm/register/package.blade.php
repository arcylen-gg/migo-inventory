@extends("mlm.register.layout")
@section("content")
<form method="post" class="register-submit" action="/member/register/package/submit" >
{!! csrf_field() !!}
<div class="package">
  <div class="container-fluid text-center">
    <div class="title"><h2>Create a Brown Package</h2></div>
    <div class="sub"></div>
    <div class="membership">
      <div class="row clearfix">
        <div class="col-md-4 product_choice">
          <div class="holder">
            <br>
            <div class="img">
              <img style="object-fit: contain; height: 250px; width: 100%; " class="" src="/assets/mlm/img/placeholder.jpg">
            </div>
            <div class="text-holder">
              <div class="name match-height">
                <div class="radio">
                  <label style="font-size: 20px;"><input type="radio" class=other_membership hide> Test Product Name</label>
                </div>
              </div>
              <div class="membership-price" style="font-size: 15px;">PHP. 50,000.00</div>   
              <br> 
              <div class="choose_prodcut">
                <a href="javascript:" class="view_details modal-button-primary btn btn-md btn-blue">
                  CHOOSE PRODUCT
                </a>
              </div>    
              <br>   
              <div class="view_details">
                <a href="javascript:" class="view_details modal-button-primary btn btn-md btn-blue" onClick="$('#modal_id_1').modal('toggle');">
                  VIEW DETAILS
                </a>
              </div>     
              <br>       
            </div>
            <section>
              <!-- Modal -->
              <div class="modal fade" id="modal_id_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header clearfix">
                      Test Product name
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="img clearfix">
                        <div class="col-md-4 hide">
                          <img style="object-fit: contain; height: 250px; width: 100%; " class="" src="/assets/mlm/img/placeholder.jpg">
                        </div>
                        <div class="col-md-12">
                          <div style="width: 100%; overflow-x: auto;">
                          <center>Test Product Description Desu Yo Ne</center>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            </section>

          </div>              
        </div>
      </div>
    </div>
    <div class="button-holder">
      <br>
      <button class="modal-button btn btn-green btn-lg" onClick="location.href='/member/register/shipping'">PROCEED TO PAYMENT</button>
    </div>
  </div>
</div>
</form>

@endsection
@section('css')
<style type="text/css">
  .active_package
  {
    /*#5C3424*/
    /*border: 1px solid #5C3424;*/
    background-color: #d0ebf2;
  }
  .hove_package
  {
    border: 1px solid #d0ebf2;
  }
  .modal-button
  {
    color: #21cc21;
      border-color: #21cc21;
      background-color: transparent;
  }
  .modal-button-primary
  {
      color: #286090;
      border-color: #286090;
      background-color: transparent;
  }
</style>
@endsection
@section('script')
<script src="/assets/front/js/match-height.js"></script>
<script type="text/javascript">
$( ".product_choice" ).hover(function (){
  $('.product_choice').removeClass('hove_package');
  $(this).addClass('hove_package');
});
$(".match-height").matchHeight();
$('.product_choice').on('click', function(){
    $('.product_choice').removeClass('active_package');
    $(this).addClass('active_package');
    var membership_id = $(this).attr('membership_id');
    $('.other_membership').prop('checked', false);
    console.log(membership_id);
    $('.membership_id_' +  membership_id).prop('checked', true); 
});
  $(document).on("submit", ".register-submit", function(e)
  {
      var data = $(e.currentTarget).serialize();
      var link = $(e.currentTarget).attr("action");
      $('#load').removeClass('hide');
      submit_form_register(link, data);
      e.preventDefault();
      
  })
  function submit_form_register(link, data)
  {
      
      $.ajax({
          url:link,
          dataType:"json",
          data:data,
          type:"post",
          success: function(data)
          {
            $('#load').addClass('hide');
            if(data.status == 'warning')
            {
              var message = data.message;
              $.each( message, function( index, value ){
            toastr.warning(value);
        });
            }
            else if(data.status == 'success')
            {
              window.location = data.link;                
            }
          },
          error: function()
          {
              $('#load').addClass('hide');
          }
      })
  }
</script>


@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="assets/mlm/css/register-package.css">
@endsection