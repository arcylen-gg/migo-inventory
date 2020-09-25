<form class="global-submit" method="post" action="{{$action}}">

    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <input type="hidden" name="event_id" value="{{$event->event_id or ''}}"/>
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">{{$process or 'Create'}} Events</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="hidden" class="upload_image_1" name="event_thumbnail_image" value="{{$event->event_thumbnail_image or '/assets/front/img/default.jpg'}}">    
                                    <label for="basic-input">Event Thumbnail</label>
                                    <div class="img">
                                        <img class="match-height img-responsive image-put-1 image-gallery image-gallery-single" key="1" src="{{$event->event_thumbnail_image or '/assets/front/img/default.jpg'}}" style="height: 150px;width: 150px; object-fit: cover; border: 1px solid #ddd;margin:auto">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="hidden" class="upload_image_2" name="event_banner_image" value="{{$event->event_banner_image or '/assets/front/img/default.jpg'}}">    
                                    <label for="basic-input">Event Banner</label>
                                    <div class="img">
                                        <img class="match-height img-responsive image-put-2 image-gallery image-gallery-single" key="2"  src="{{$event->event_banner_image or '/assets/front/img/default.jpg'}}" style="height: 250px;width: 250px; object-fit: cover; border: 1px solid #ddd;margin:auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        <label>Available For :</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                        <input type="text" name="event_number_attendee" class="form-control input-sm" placeholder="No." value="{{$event->event_number_attendee or ''}}">
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox">
                                      <label><input type="checkbox" value="1" name="event_member" {{isset($event_member) ? ($event_member != null ? 'checked' : '') :''}}>Members</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox">
                                      <label><input type="checkbox" value="1" name="event_guest" {{isset($event_guest) ? ($event_guest != null ? 'checked' : '') :''}}>Guest</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Title</label>
                                    <input type="text" name="event_title" class="input-sm form-control" value="{{$event->event_title or ''}}">
                                </div>      
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Sub Title</label>
                                    <input type="text" name="event_sub_title" class="input-sm form-control" value="{{$event->event_sub_title or ''}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Date</label>
                                    <input class="datepicker input-sm form-control" type="text" value="{{$event->event_date or date('m/d/Y')}}" name="event_date">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Description</label>
                                    <textarea class="form-control input-sm tinymce" name="event_description">{{$event->event_description or ''}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
    </div>
</form>
<script type="text/javascript">
    $(function()
    {
        $(".datepicker").datepicker();
    });
</script>
<script type="text/javascript" src="/assets/member/js/tinymce.min.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>

<script type="text/javascript">
    function submit_selected_image_done(data) 
    { 
        var image_path = data.image_data[0].image_path;

        if(data.akey != 0) 
        {
            $('.upload_image_'+data.akey).val(image_path);
            $('.image-put-'+data.akey).attr("src", image_path);
        }
    }
</script>