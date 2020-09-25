<form  action="/members/wallet-refill-request" method="post" enctype="multipart/form-data">
    {{ csrf_field()  }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title redeemable-add-page-title">{{$page}}</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Slot</label>
                            <select name="slot" class="form-control slot-owner" style="width:100;">
                                @foreach($slot_owner as $owner)
                                <option class="form-control" value="{{$owner->slot_no}}">{{$owner->slot_no}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="text-left col-md-12">
                            <label>Amount</label>
                            <input type="number" min="1" autocomplete="off" id="basic-input" name="amount" class="form-control requested-amount" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="text-left col-md-6 refill-fee">
                            <label>Processing fee: </label>
                            <label class="processing-fee">{{$fee}}</label>
                        </div>
                        <div class="text-right col-md-6 amount-container">
                            
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-5">
                            <label>Attachment:</label>
                            <input onchange="onImageSelect(this);" type="file" style="width:100%" name="attachment" value="Add Image" class="attachment">
                        </div>
                        <div class="col-md-7">
                            <img width="100%" height="auto" style="max-height:200px" class="img-holder">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Note:</label>
                            <textarea class="form-control" style="width:99%;height:auto;" name="remarks"></textarea>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><span class="fa fa-close" /> Close</button>
        <button class="btn btn-primary btn-custom-primary send" type="submit"><span class="fa fa-money" />  Send</button>
    </div>
</form>

<script type="text/javascript">
function success_wallet_refill(data)
{
    toastr.success("Wallet request submitted");
    data.element.modal("hide");
}
function error_wallet_refill(data)
{
    toastr.error("ERROR");
}
</script>
<script type="text/javascript">
function onImageSelect(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('.img-holder')
        .attr('src', e.target.result)
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/wallet_refill.js?v=1"></script>