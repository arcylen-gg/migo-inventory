<form class="global-submit" role="form" action="/member/project/project_list/add" method="post">
	{{ csrf_field()  }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">CREATE NEW PROJECT</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="row">
	        <div class="clearfix modal-body"> 
	            <div class="form-horizontal">
	                <div class="form-group">
	                    <div class="col-md-6">
	                        <label for="basic-input">Project Name</label>
							<input id="basic-input" class="form-control" name="project_name" placeholder="Name">
	                    </div>
	                    <div class="col-md-6">
	                        <label for="basic-input">Project Type</label>
	                        <select name="project_type_id" class="form-control">
	                        	@foreach($_type as $type)
	                        	<option value="{{ $type->project_type_id }}">{{ $type->project_type_name }}</option>
	                        	@endforeach
	                        </select>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="col-md-6">
	                        <label for="basic-input">E-Mail</label>
	                       	<input id="basic-input"  class="form-control" name="project_email" placeholder="E-Mail">
	                    </div>
	                    <div class="col-md-6">
	                        <label for="basic-input">Contact Number</label>
	                        <input id="basic-input" class="form-control" name="project_contact" placeholder="Contact Number">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="col-md-12">
	                        <label for="basic-input">Date Start</label>
	                       	<input id="basic-input" type="date"  class="form-control" name="project_date" placeholder="Date Start">
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
function success_project_create(data)
{
	toastr.success("New Project Saved");
	data.element.modal("hide");
	project_list.action_load_table();
}
</script>