<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">{{$type}} NO: {{$sir_id}}</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container" >
 <div class="panel-body form-horizontal">
      	@if(isset($tablet))
	        <div class="form-group text-center">
	            <div class="col-md-6">
	            	<a size="md" link="/tablet/pis/sir/{{$sir_id}}/confirm" class="btn btn-primary popup">Confirm Load Out Form</a>
	            </div>
	            <div class="col-md-6">
					<a size="md" class="btn btn-primary popup" link="/tablet/pis/sir/{{$sir_id}}/reject">Reject Load Out Form</a>
	            </div>
	        </div>
        @endif
        <div class="form-group">
            <div class="col-md-12">            
			    <iframe width="100%" height="1200px" style="overflow-y: hidden;" src="/member/pis/sir/view_pdf/{{$sir_id}}/{{$type_code}}"></iframe>
			</div>
		</div>
 </div>
</div>
<script>
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
