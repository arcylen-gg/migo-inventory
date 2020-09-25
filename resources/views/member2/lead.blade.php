<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">COPY LEAD LINK BELOW</h4>
</div>
<div class="modal-body clearfix">
	<input class="leadlink" style="width: calc(100% - 200px); padding: 5px;" class="form-control pull-left" type="text" value="{{ $url }}/{{ urlencode(request('slot_no')) }}"/>
    <button onclick="return ClipBoard()" class="pull-right btn btn-custom-primary" style="width: 190px;">COPY LINK</button>
</div>

<script type="text/javascript">
    
function ClipBoard()
{
   copyToClipboard(".leadlink");
}

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
}


</script>