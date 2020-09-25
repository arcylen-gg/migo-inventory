<form class="global-submit form-horizontal padding-15" action="{{$action}}" method="POST">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <input type="hidden" value="{{$id}}" name="data_id"> 
    <div class="form-group">
        <div class="col-md-12 text-center">
            <h3>{{$message}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-center padding-5">
            <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        </div>
        <div class="col-md-6 text-center padding-5">
            {!!$btn!!}
        </div>
    </div>
</form>