@extends("mlm.register.layout")

@section("content")
<form method="post" action="/member/register/payment/submit" >
    {!! csrf_field() !!}
<div class="payment">
  <div class="container-fluid">
    <div class="title">How do you want to pay?</div>
    <div class="sub hide">Aenea commodo ligula eget dolor.</div>
    <div class="row clearfix">
      <div class="col-md-8">
        <div class="payment-container text-left" style="margin-top:0px">
          <h2>Choose Payment Method</h2>
          @if (count($errors) > 0)
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
          <div class="row clearfix">
            <div class="col-md-8">
              <div class="holder-holder">
                @if(count($_payment_method) != 0)
                  @foreach($_payment_method as $payment_method)
                    <div class="choose-payment-method holder" method_id="{{ $payment_method->method_id }}" description="{{ $payment_method->link_description }}">
                      <div class="match-height" style="line-height: 12.5px;">{{ $payment_method->method_name }}</div>
                      <div class="image" style="margin-top: 7.5px;">
                        <img src="{{ $payment_method->image_path ? $payment_method->image_path : '/assets/front/img/default.jpg' }}">
                      </div>
                      <div class="radio" style="margin-bottom: 0;">
                        <label >
                            <input class="radio" type="radio" name="payment_method_id" value="{{ $payment_method->method_id }}">
                        </label>
                      </div>
                    </div>
                  @endforeach 
                @else
                  <div class="text-center"><h3>No Payment Method Available</h3></div>
                @endif
              </div>
              <div class="details clearfix">
                <div class="detail-holder">
                  <div class="details-text">Kindly choose a payment method which you are most comfortable with paying.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 order-summary-container">
         <div class="hold-container">
            <div class="hold-header">SUMMARY</div>
            <div class="hold-content">
                <div class="cart-summary">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>PRODUCT</th>
                           <th>QTY</th>
                           <th>PRICE</th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                              <td>Test Product Name</td>
                              <td>1</td>
                              <td style="padding-left: 0; padding-right: 0;">P 1.00</td>
                              <td style="padding-left: 0px; padding-right: 0px; width: 10px;"><a style="color: red;" href="/cart/remove?redirect=1&amp;variation_id=45"><i class="fa fa-close"></i></a></td>
                           </tr>
                         <tr>
                           <td></td>
                           <td class="text-right"><b>Subtotal</b></td>
                           <td colspan="2" class="total text-left" style="word-break: break-all;">P 123.00</td>
                        </tr>
                        <tr>
                           <td></td>
                           <td class="text-right"><b>Shipping Fee</b></td>
                           <td colspan="2" class="total text-left" style="word-break: break-all;">INCLUDED</td>
                        </tr>
                         <tr>
                           <td></td>
                           <td class="text-right"><b>Total</b></td>
                           <td colspan="2" class="total text-left" style="word-break: break-all;">P 123.00</td>
                        </tr>
                     </tbody>
                  </table>
                  <!-- <div style="margin-top: 15px; font-size: 16px; font-weight: 700; text-align: center;">Free shipping for orders above ₱ 500.00</div> -->
               </div>
            </div>
         </div>
      </div>
    </div>
    <!-- <div class="information-container">
      <div class="information-title">Delivery Information</div>
      <div class="form-holder">
        <div class="row clearfix">
          <div class="col-md-6">
            <div class="form-group">
              <label>First Name</label>
              <input type="text" class="form-control input-lg" name="first_name">
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="text" class="form-control input-lg" name="last_name">
            </div>
            <div class="form-group">
              <label>Contact Information</label>
              <input type="text" class="form-control input-lg" name="contact_info">
            </div>
            <div class="form-group">
              <label>Other Contact Information</label>
              <input type="text" class="form-control input-lg" name="contact_other">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Shipping Method</label>
              <select class="form-control input-lg">
                <option></option>
              </select>
            </div>
            <div class="form-group">
              <label>Complete Shipping Address</label>
              <textarea class="form-control input-lg" name="shipping_address"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <div class="button-holder">
      <button class="btn btn-green btn-lg">PROCEED</button>
    </div>
  </div>
</div>
</form>
@endsection
@section('script')
<script type="text/javascript">
event_choose_payment_method();
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
    $('.match-height').matchHeight();
    $('input[name="payment_type"]').removeProp("checked");
    $('input[name="payment_type"]').removeAttr("checked");
    $('input[name="payment_type"]').change(function(event) 
    {
      var payment_type = $(event.currentTarget).val();

      if(payment_type == "dragonpay")
      {
        $('form').removeClass('register-submit');
      }
      else
      {
        $('form').addClass('register-submit');
      }
    });

   function event_choose_payment_method()
   {
      $(".choose-payment-method").unbind("click");
      $(".choose-payment-method").bind("click", function(e)
      {
         $(".checkout-summary .loader-here").removeClass("hidden");
         $(e.currentTarget).find(".radio").prop("checked", true);

         var description = $(e.currentTarget).attr("description");
         $(".details-text").html(description);

         var method_id = $(e.currentTarget).attr("method_id");
      });
   }
</script>
@endsection
@section("css")
<link rel="stylesheet" href="themes/ecommerce-1/css/checkout_payment.css">
<link rel="stylesheet" type="text/css" href="assets/mlm/css/register-payment.css">
@endsection