<form action="distributor/change_password" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="list-group-item form-horizontal">
                        <h4>
                            CHANGE PASSWORD
                        </h4>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Old Password</label>
                            <div class="col-md-10">
                                <input type="password" name="opw" id="opw" class="form-control" value="">     
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">New Password</label>
                            <div class="col-md-10">
                                <input type="password" name="npw" id="npw" class="form-control" value="">     
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Confirm New Password</label>
                            <div class="col-md-10">
                                <input type="password" name="cpw" id="cpw" class="form-control" value="">     
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 pull-right text-right">
                                <input type="submit" class="btn btn-success" value="CHANGE PASSWORD">
                            </div>
                        </div>
                    </div>
                </form>