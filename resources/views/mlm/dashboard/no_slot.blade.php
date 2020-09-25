 <form class="form-horizontal" method="POST">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 <table class="table table-striped">
                     <thead>
                         <tr>
                             <th colspan="2"><div class="alert alert-danger">
                                  <strong>Danger!</strong> You have no access. Claim Code to have access.
                                </div>
                             </th>
                         </tr>
                         <tr>
                             
                             <th>Pin Number</th>
                             <th><input class="form-control col-md-12" type="number" name="pin" id"1111"></th>
                         </tr>
                         <tr>
                            <th>
                               Code 
                            </th>
                            <th>
                                <input class="form-control col-md-12" type="text" name="activation" id"2222">
                            </th>
                         </tr>
                         <tr>
                             <th colspan="2"><button class="btn btn-primary col-md-12" type="submit" name="sbmtclaim" >Claim Code</button></th>
                         </tr>
                     </thead>
                 </table>
             </form>
             <table class="table table-condensed table-responsive" width="100%">

            <thead>
                <tr>
                    <th>Pin</th>
                    <th data-hide="phone">Code</th>
                    <th data-hide="phone">Type</th>
                    <th data-hide="phone">Obtained From</th>
                    <th data-hide="phone,phonie">Membership</th>
                    <th data-hide="phone,phonie">Locked</th>
                    <th data-hide="phone,phonie">Product Set</th>
                    <th data-hide="phone,phonie">Status</th>
                    <th data-hide="phone,phonie,tablet"></th>
                    <th data-hide="phone,phonie,tablet"></th>
                </tr>
            </thead>
            <tbody>

                <tr class="tibolru" loading="">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="check">
                            <input type="checkbox" class="checklock"  disabled="disabled">
                            <div class="bgs">
                            </div>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td><a style="cursor: pointer;" class="createslot" value="" >Create Slot</a></td>

                    <td class="hide"><a style="cursor: pointer;" class="transferer" value="" val="">Transfer Code</a></td>
                </tr>
            </tbody>
        </table>
        </div>
    <div class="remodal" data-remodal-id="create_slot" data-remodal-options="hashTracking: false">
   <button data-remodal-action="close" class="remodal-close pull-right"></button>
    <div class="header">
        Create Slot
    </div>
    <!--<img src="/resources/assets/frontend/img/sobranglupet.png" style="max-width: 100%; margin: 20px auto">-->
    <div class="sponsornot"></div>
    <div class="col-md-10 col-md-offset-1 para">
        <form class="form-horizontal" method="POST" id="createslot" action="/distributor">
            <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group para">
                <label for="1" class="col-sm-3 control-label">Sponsor</label>
                <div class="col-sm-9">
                    <input type="hidden" value="1" id="checkclass">
                    <select class="sponser form-control" id="1" name="sponsor">
                        <option value="">Slot #</option>
                    </select>
                </div>
            </div>
            <div class="form-group para hide">
                    <label for="2" class="col-sm-3 control-label">Placement</label>
                    <div class="treecon col-sm-9">
                        <select class="tree form-control hidden" id="2" name="placement" required disabled>
                            <option value="">Input a slot sponsor</option>
                        </select>
                        <input type="number" class="form-control placement-input" name="placement" value="">
                    </div>
                    <input type="hidden" id="code_number" value="" name="code_number">
                </div>
                <div class="form-group para hide">
                    <label for="3" class="col-sm-3 control-label">Position</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="3" name="slot_position">
                            <option value="left" selected>Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-md-12" style="padding: 5px;">
            <button class="cancel button btn btn-danger col-md-6" type="button" data-remodal-action="cancel">Cancel</button>
            <button class="c_slot button btn btn-primary col-md-6"  type="button" name="c_slot">Create Slot</button>
            </div>
            <span class='loadingicon' style="margin-left: 50px; display:none"><img class='loadingicon' src='//resources/assets/img/small-loading.GIF'></span>
            </form>
            <!-- POPUP FOR CONFIRMATION / USE CODE -->
            <div class="remodal confirm_slot-slot" data-remodal-id="confirm_slot" data-remodal-options="hashTracking: false">
                <button data-remodal-action="close" class="remodal-close"></button>
                <div class="header">
                <img src="//resources/assets/frontend/img/icon-plis.png">
                Confirm Slot
                </div>
                <img src="//resources/assets/frontend/img/sobranglupet.png" style="max-width: 100%; margin: 20px auto">
                <div class="sponsornot"></div>
                <div class="col-md-10 col-md-offset-1 para">
                <div class="form-group para hide">
                    <label for="2" class="col-sm-3 control-label">Placement Owner</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="placement_owner" disabled>
                    </div>
                </div>
                <div class="form-group para">
                    <label for="2" class="col-sm-3 control-label">Sponsor Owner</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="sponsor_owner" disabled>
                    </div>
                </div>
                <button class="canceler button" type="button">Back</button>
                <button class="confirmer button" type="button" name="c_slot">Create Slot</button>
                <span class='loadingiconer' style="margin-left: 50px;"><img class='loadingiconer' src='//resources/assets/img/small-loading.GIF'></span>
                </div>    
                
                <br>
            </div>