<form  action="/members/upload-attachment" method="post" enctype="multipart/form-data">
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
                        <div class="col-md-5">
                            <input type="hidden" name="id" value="{{$id}}">
                            <label>Attachment:</label>
                            <input onchange="onImageSelect(this);" type="file" style="width:100%" name="attachment" value="Add Image" class="attachment" required>
                        </div>
                        <div class="col-md-7">
                            <img width="100%" height="auto" style="max-height:200px" class="img-holder">
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><span class="fa fa-close" /> Close</button>
        <button class="btn btn-primary btn-custom-primary send" type="submit"><span class="fa fa-money" />  Upload</button>
    </div>
</form>

<script type="text/javascript">
function success_upload(data)
{
    toastr.success("Attachment uploaded");
    data.element.modal("hide");
}
function error_upload(data)
{
    toastr.error("Error uploading file");
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

<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/wallet_refill.js"></script>