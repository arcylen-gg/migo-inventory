<form class="form-horizontal import-form" role="form" action="javascript:" method="post">
    <input class="import-token" type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">IMPORT SLOT FOR TESTING</h4>
	</div>
	<div class="modal-body clearfix">
        <div class="form-group">
            <div class="col-md-12">            
                <label>FILE IMPORT (.xlsx)</label>
                <input  name="sponsor" type="file" class="form-control import-excel" placeholder="RANDOM (IF EMPTY)">
                <div class="text-center import-button" style="display: none;">
                    <button class="btn btn-primary btn-custom-primary start-importation" type="button">Start Importation</button>
                </div>
                <div class="show-progress" style="display: none; background-color: #fff; margin: 30px; border: 3px solid #ddd; height: 30px; overflow: hidden; border-radius: 5px;">
                    <div class="progress" style="width: 50%; height: 30px; background-color: #0081a5; border-radius: 0;"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">            
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th class="text-center">EMAIL</th>
                            <th class="text-center">FIRST NAME</th>
                            <th class="text-center">LAST NAME</th>
                            <th class="text-center">SLOT NO</th>
                            <th class="text-center">DATE CREATED</th>
                            <th class="text-center">SPONSOR</th>
                            <th class="text-center">PLACEMENT</th>
                            <th class="text-center">PACKAGE<br>NUMBER</th>
                            <th class="text-center">PASSWORD</th>
                            <th width="200px;" class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="import-slot-list">
                        <tr>
                            <td class="text-center" colspan="10">NO SLOT TO IMPORT YET</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
	</div>


	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary start-importation" type="button">Start Importation</button>
	</div>
</form>

<style type="text/css">
    #global_modal .modal-lg
    {
        width: 95%;
    }
</style>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script> -->
<script src="/assets/external/xlsx.core.min.js"></script>
<script src="/assets/member/js/mlm/mlm_developer_import.js?version=10"></script>