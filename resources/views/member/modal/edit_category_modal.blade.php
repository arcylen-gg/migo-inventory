<form class="global-submit" method="POST" action="/member/item/category/update_category">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Create new category</h4>
  </div>
  <div class="modal-body modallarge-body-layout background-white form-horizontal">   
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <input type="hidden" name="type_id" value="{{$category->type_id}}">
    <div class="form-group">
      <div class="col-md-12">
        <label>Category Name</label>
        <input type="text" name="type_name" class="form-control" value="{{$category->type_name}}" placeholder="" required>
      </div>      
    </div>    
    <div class="form-group {{isset($equipment_category) ? ($equipment_category == 1 ? '' : 'hidden') : 'hidden'}}">
      <div class="col-md-12">
        <div class="checkbox">
          <label><input type="checkbox" class="" name="equipment_category" value="1" {{$category->equipment_type_category != 0 ? 'checked="checked"':''}}>Equipment Type</label>
        </div>
      </div>
    </div>
    <div class="form-group text-center">
      <div class="col-md-12">
        <label>Category Image</label>
        <div class="category-image" style="margin-bottom: 7.5px;">
          <img style="height: 150px; width: auto; border: 1px solid #bcbcbc;" src="{{$category->type_image}}">
        </div>
        <button class="btn btn-primary image-gallery image-gallery-single" key="category-image">Upload</button>
        <input class="category-image-path" type="hidden" name="type_image" class="form-control" placeholder="" value="{{$category->type_image}}" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-12">
        <div class="checkbox">
          <label><input type="checkbox" class="is_sub_category" name="is_sub_category" value="1" {{$category->type_parent_id != 0 ? 'checked="checked"':''}}>Is sub category</label>
        </div>
        <input type="text" class="form-control readonly parent_category cursor-pointer padding-r-10" value="{{$category->type_parent_id != 0 ? $parent->type_name:''}}" {{$category->type_parent_id != 0 ? '':'disabled="true"'}}>
          <i class="fa fa-angle-down margin-top-3 pos-absolute" aria-hidden="true" style="right: 24px; top: 42px;"></i>
        </input>
        <div class="pos-absolute custom-form-control display-none parent_category-list">
            <div class="list-group list-group-root well list-group-category">
              {!!$_category['html']!!}
            </div>

        </div>
        <input type="hidden" name="hidden_parent_category" value="{{$category->type_parent_id != 0 ? $parent->type_id:''}}" class="hidden_parent_category">

      </div>
      
    </div>

    <div class="form-group">
      <div class="col-md-12">
        <label>Category Type</label>
        <select class="form-control type_category" name="type_category" required>
            <option value="inventory" {{$category->type_category == "inventory" ? 'selected="selected"':''}}>Inventory</option>
            <option value="non-inventory" {{$category->type_category == "non-inventory" ? 'selected="selected"':''}}>Non-inventory</option>  
            <option value="services" {{$category->type_category == "services" ? 'selected="selected"':''}}>Services</option>
            <option value="bundles" {{$category->type_category == "bundles" ? 'selected="selected"':''}}>Bundles</option>
        </select>
      </div>
    </div>

  </div>
  <div class="modal-footer">  
    <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>

    <button class="btn btn-custom-primary btn-save-submit" type="submit" data-url="">Update category</button>
  </div>
</form>
<script type="text/javascript" src="/assets/member/js/category_plugin.js"></script>
<script type="text/javascript">
function submit_selected_image_done(data) 
{
  var image = data.image_data[0].image_path;

  $('.category-image-path').val(image);
  $('.category-image img').attr("src", image); 
}
</script>