<form class="global-submit" role="form" action="/member/project/project_list/add" method="post">
	{{ csrf_field()  }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{$project_name}}</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="row">
	        <div class="clearfix modal-body"> 
	            <div class="form-horizontal">
	                
	                <div class="form-group">
	                    <div class="col-md-12">
	                        <label for="basic-input">Assignee</label>
	                       	<input id="basic-input"  class="form-control" name="assignee">
	                    </div>
	                    <div class="col-md-12 prediction">
	                    	
	                    </div>
	                </div>

	                <div class="form-group">
	                    <div class="col-md-12">
	                        <label for="basic-input">Task</label>
	                       	<input id="basic-input"  class="form-control" name="task">
	                    </div>
	                </div>

	                <div class="form-group">
	                    <div class="col-md-6">
	                        <label for="basic-input">Hours</label>
	                       	<input id="basic-input" type="number" min="1" class="form-control" name="hours">
	                    </div>
	                    <div class="col-md-6">
	                        <label for="basic-input">Priority</label>
	                       	<input id="basic-input" type="number" min="1" max="10" class="form-control" name="priority">
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