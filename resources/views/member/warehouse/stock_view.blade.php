<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Report</h4>
</div>
<div class="modal-body modallarge-body-layout background-white form-horizontal menu_container" >
    <iframe width="100%" height="1200px" style="overflow-y: hidden;" src="/member/item/warehouse/stock_input_report/{{$slip_id}}"></iframe>
</div>
<script>
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
