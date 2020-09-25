<form class="global-submit" method="post" action="/member/page/events/archived?id={{$event->event_id}}&action={{$action}}">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{strtoupper($action)}}</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="form-group">
			<div class="col-md-4">
				<img class="match-height img-responsive" key="2"  src="{{$event->event_thumbnail_image}}" style="height: 100px;width:100px; object-fit: cover; border: 1px solid #ddd;margin:auto">
			</div>
			<div class="col-md-8 text-center">
				<h4>
					Do you wan't to <b>{{ucfirst($action)}}</b> this event ?
				</h4>
				<br>
				<h4><b>{{ucwords($event->event_title)}}</b></h4>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
	</div>
</form>