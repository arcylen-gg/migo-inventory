@if($page=='ADD NEW REDEEMABLE')
<form class="global-submit" role="form" action="/member/item/redeemable/add" method="post">
@else
<form class="global-submit" role="form" action="/member/item/redeemable/modify" method="post">
@endif
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
                        <div class="col-md-6">
                            <label for="basic-input">Item Name</label>
                            @if(isset($row))
                            @foreach($row as $r)
                            <input id="basic-input"  class="form-control" name="item_name" value="{{$r->item_name}}">
                            <input type="hidden" class="form-control" name="item_redeemable_id" value="{{$r->item_redeemable_id}}">
                            @endforeach
                            @else
                            <input id="basic-input"  class="form-control" name="item_name">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="basic-input">Item Quantity</label>
                            @if(isset($row))
                            @foreach($row as $r)
                            <input id="basic-input" type="number" class="form-control" name="quantity" value="{{$r->quantity}}">
                            @endforeach
                            @else
                            <input id="basic-input" type="number" class="form-control" name="quantity">
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Item Description</label>
                            @if(isset($row))
                            @foreach($row as $r)
                            <textarea class="form-control" style="width:99.95%" name="item_sales_information">{{$r->item_description}}</textarea>
                            @endforeach
                            @else
                            <textarea class="form-control" style="width:99.95%" name="item_sales_information"></textarea>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Redeemable Points</label>
                            @if(isset($row))
                            @foreach($row as $r)
                            <input id="basic-input" type="number"  class="form-control" name="redeemable_points" value="{{$r->redeemable_points}}">
                            @endforeach
                            @else
                            <input id="basic-input" type="number"  class="form-control" name="redeemable_points">
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <input type="submit" onsubmit="return false" style="width:70%" value="Add Image" class="btn btn-primary btn-custom-primary image-gallery image-gallery-single" key="123">
                        </div>
                        <div class="col-md-8">
                            @if(isset($row))
                            @foreach($row as $r)
                            <img src="{{$r->image_path}}" width="100%" height="auto" style="max-height:200px" class="img-holder">
                            @endforeach
                            @else
                            <img src="" width="100%" height="auto" style="max-height:200px" class="img-holder">
                            @endif
                        </div>
                        <div class="col-md-0">
                            @if(isset($row))
                            @foreach($row as $r)
                            <input type="hidden" class="form-control image_path" name="image_path" value="{{$r->image_path}}">
                            @endforeach
                            @else
                            <input type="hidden" class="form-control image_path" name="image_path">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Save</button>
    </div>
</form>

<script type="text/javascript">
function success_project_create(data)
{
    if( $('.redeemable-add-page-title').text()=="ADD NEW REDEEMABLE")
    {
        toastr.success("New Redeemable Item Saved");
    }
    else
    {
        toastr.success("Redeemable Item Saved");
    }
    data.element.modal("hide");
    redeemable.action_load_table();
}
function submit_selected_image_done(data)
{
    var image_path = data.image_data[0].image_path;

    if(data.akey == 123)
    {
        $('.image_path').val(image_path);
        $('.img-holder').attr("src", image_path);
    }

}
</script>