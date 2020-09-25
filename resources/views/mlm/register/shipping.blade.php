@extends("mlm.register.layout")
@section("content")
<form method="post" class="register-submit" action="/member/register/shipping/submit" >
    {!! csrf_field() !!}
<div class="payment">
  <div class="container-fluid">
    <div class="title">Getting Details</div>
    <div class="information-container">
      <div class="information-title">Delivery Information</div>
      <div class="form-holder">
        <div class="row clearfix">
          <div class="col-md-12">
            <div class="form-group">
              <label>Province</label>
              <select class="form-control input-lg province" name="customer_state">
                @foreach($_province as $key=>$locale)
                  <option value="{{$locale->locale_id}}">{{$locale->locale_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group load-data-municipality">
               <div id="municipality">
                 <label>City / Municipality</label>
                 <select class="form-control input-lg municipality" name="customer_city"z>
                   @foreach($_city as $key=>$locale)
                     <option value="{{$locale->locale_id}}">{{$locale->locale_name}}</option>
                   @endforeach
                 </select>
               </div>
            </div>
            <div class="form-group load-data-barangay">
               <div id="barangay">
                 <label>Barangay</label>
                 <select class="form-control input-lg barangay" name="customer_zip">
                   @foreach($_barangay as $key=>$locale)
                     <option value="{{$locale->locale_id}}">{{$locale->locale_name}}</option>
                   @endforeach
                 </select>
               </div>
            </div>
            <div class="form-group">
              <label>Complete Shipping Address</label>
              <textarea class="form-control input-lg" name="customer_street"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="button-holder">
      <button class="btn btn-green btn-lg" onClick="location.href='/member/register/payment'">PROCEED</button>
    </div>
  </div>
</div>
</form>
@endsection
@section('script')
<link rel="stylesheet" type="text/css" href="assets/mlm/css/register-payment.css">
<script type="text/javascript">
  $(document).on("submit", ".register-submit", function(e)
        {
            var data = $(e.currentTarget).serialize();
            var link = $(e.currentTarget).attr("action");
            $('#load').removeClass('hide');
            submit_form_register(link, data);
            e.preventDefault();
            
        });

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

    $(document).on("change", "select.province", function()
    {
      $("select.municipality").html("<option> Loading .... </option>");
      $("select.barangay").html("<option> Loading .... </option>");
      $(".load-data-municipality").load("/member/register/shipping?city_parent=" + $(this).val() + " #municipality");
      $(".load-data-barangay").load("/member/register/shipping?city_parent=" + $(this).val() + " #barangay");
    })

    $(document).on("change", "select.municipality", function()
    {
      $("select.barangay").html("<option> Loading .... </option>");
      $(".load-data-barangay").load("/member/register/shipping?barangay_parent=" + $(this).val() + " #barangay");
    })
</script>
@endsection
@section("css")

@endsection