@extends('mlm.layout')
@section('content')
<style type="text/css">
.newsimage
{
    width: 100%;
    height: 250px;
    min-height: 250px;
    max-height: 250px;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<div class="row">
    <div class="col-md-6 col-lg-8">
        <div class="cover-holder panel panel-default panel-block panel-title-block">
            <div class="panel-headings clearfix">
                <img class="banner-img" src="/assets/mlm/img/banner-shop.jpg">
                <div class="shadow">
                    <img src="/assets/mlm/img/shadow.png">
                </div>
                <div class="cover-container">
                    <div class="cover-img">
                        <img src="/assets/mlm/img/pic-shop.jpg">
                    </div>
                    <div class="cover-text">
                        <div class="name">onlineshopping.com</div>
                        <div class="date">This account is a member since June 03, 2015 - 11:09 PM</div>
                        <div class="email">sonnyboymata@yahoo.com</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs panel panel-default panel-block" style="margin-top: 35px;">
            <li class="<?php echo (!isset($_GET["tab"]) && !isset($_GET["tabpw"])  && !Request::input('gc') && !Request::input('wallet')  ? "active" : ""); ?>"><a href="#user-overview" data-toggle="tab">OVERVIEW</a></li>
            <li class="<?php echo (isset($_GET["tab"]) && !isset($_GET["tabpw"])   && !Request::input('gc') && !Request::input('wallet') ? "active" : ""); ?>"><a href="#user-settings" data-toggle="tab">EDIT PROFILE</a></li>  
            <li class="<?php echo (!isset($_GET["tab"]) && isset($_GET["tabpw"])   && !Request::input('gc') && !Request::input('wallet') ? "active" : ""); ?>"><a href="#change-password" data-toggle="tab">CHANGE PASSWORD</a></li>      
        </ul>

        <div class="tab-content panel panel-default panel-block">
            <!-- OVERVIEW -->
            <div style="" class="tab-pane <?php echo (!isset($_GET["tab"]) && !isset($_GET["tabpw"]) && !Request::input('gc')  && !Request::input('wallet') ? "active" : ""); ?>" id="user-overview">   
                <ul class="list-group">  
                    <li class="list-group-item form-horizontal">
                        <h4>              
                            Account Information
                        </h4> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Account Username</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Slot Number</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Membership</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group hide">
                            <label for="first-name" class="col-md-2 control-label">Total Profit (+)</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group hide">
                            <label for="first-name" class="col-md-2 control-label">Account Wallet</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>  
                    </li>
                    <li class="list-group-item form-horizontal">
                        <h4>
                            Income Summary
                        </h4> 
                        @if (Session::has('message'))
                           <div class="alert alert-info">{{ Session::get('message') }}</div>
                        @endif
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Total Direct</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Total Indirect</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group hide">
                            <label for="first-name" class="col-md-2 control-label">Total Pipeline</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group hide">
                            <label for="first-name" class="col-md-2 control-label">Total Binary</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group hide">
                            <label for="first-name" class="col-md-2 control-label">Total Income</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                    </li>



                    <li class="list-group-item form-horizontal hide">
                        <h4>
                            Unilevel
                        </h4> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Personal PV</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value=" PPV" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Group PV</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value=" GPV" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Required Personal PV</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value=" PPV" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Unilevel Status</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-horizontal hide">
                        <h4>
                            Binary Today
                        </h4>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">START LEFT</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">START RIGHT</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">REMAIN LEFT</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">REMAIN RIGHT</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">EARNED TODAY</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">FLUSHOUT TODAY</label>
                            <div class="col-md-10">
                                <input id="first-name" class="form-control" value="" disabled="disabled">
                            </div>
                        </div> 
                    </li>
                </ul>

            </div>
            <div style="" class="tab-pane <?php echo (!isset($_GET["tab"]) && !isset($_GET["tabpw"]) && !Request::input('gc')  && !Request::input('wallet') ? "active" : ""); ?>" id="user-overview">
            <!--<h2 class=""> <center class="label label-danger col-md-12">You have no access.</center></h2>-->
            <div style="" class="tab-pane <?php echo (!isset($_GET["tab"]) && !isset($_GET["tabpw"]) && !Request::input('gc')  && !Request::input('wallet') ? "active" : ""); ?>" id="user-overview">
            @if($errors->any())
                <h4></h4>
                <div class="alert alert-danger">
                    <ul>
                        
                        <li>{{$errors->first()}}</li>
                       
                    </ul>
                </div>
            @endif
            </div>
            
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
            </div>
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
            
            <!-- EDIT PROFILE -->
            <div class="tab-pane scrollable list-group <?php echo (isset($_GET["tab"]) && !isset($_GET["tabpw"])  && !Request::input('wallet') && !Request::input('gc')? "active" : ""); ?>" id="user-settings">
                <form method="POST" action="/distributor/updateprofile">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="list-group-item form-horizontal">
                        <h4>
                            Basic Information
                        </h4>
                        <div class="form-group">
                            <label for="first-name" class="col-md-2 control-label">Full Name</label>
                            <div class="col-md-10">
                                <input id="first-name" name="first-name" class="form-control" disabled="disabled" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group-addon">
                          
                            </div>
                            <label for="first-name" class="col-md-2 control-label">Gender</label>
                            <div class="col-md-10">
                                <select name="account-gender" id="account-gender" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;">
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group-addon">
                    
                            </div>
                            <label for="first-name" class="col-md-2 control-label">Birth Month</label>
                            <div class="col-md-10">
                                <input type="date" id="datepicker" name="datepicker" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;" value="" placeholder="mm/dd/yyyy">
                            </div>
                        </div>                 
                        <div class="form-group">
                            <div class="form-group-addon">
                    
                            </div>
                            <label for="location" class="col-md-2 control-label">Country</label>
                            <div class="col-md-10">
                                <select name="account-location" id="account-location" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;">
                            
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="occupation" class="col-md-2 control-label">Addresss</label>
                            <div class="col-md-10">
                                <textarea id="address" name="address" class="form-control"></textarea>
                            </div>
                        </div>

                        <h4>
                            Contact Information
                        </h4>
                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Username</label>
                            <div class="col-md-10">
                                <input name="username" id="username" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email</label>
                            <div class="col-md-10">
                                <input name="email" id="email" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Contact Number</label>
                            <div class="col-md-10">
                                <input name="contact-number" id="contact-number" class="form-control" value="">
                            </div>
                        </div>
                        <h4>
                            Payment Portal for Encashment
                        </h4>
                        <div class="form-group">
                            <div class="form-group-addon">
                        
                            </div>
                            <label for="email" class="col-md-2 control-label">Bank</label>
                            <div class="col-md-10">
                                <select name="encashmentselect" id="encashmentselect" class="form-control" style="font-family: 'Titillium Web',sans-serif !important;">
                                    <option value="1">Bank Deposit</option>
                                    <option value="2">Cheque</option>
                                </select>
                            </div>
                        </div>
                        @if(isset($cheque_info))
                        <input type="hidden" value="{{ $chequeinfo='1'}}">
                        <input type="hidden" value="{{ $chequename=$cheque_info->cheque_name}}">
                        @else
                        <input type="hidden" value="{{ $chequeinfo='null'}}">
                        <input type="hidden" value="{{ $chequename='null'}}">
                        @endif
                        <input type="hidden" id="cheque" value="{{$chequeinfo}}">
                        <input type="hidden" id="chequename" value="{{$chequename}}">

                        @if(isset($bank_info))
                        <input type="hidden" value="{{ $bankinfo='1'}}">
                        <input type="hidden" value="{{ $bankname=$bank_info->bank_name}}">
                        <input type="hidden" value="{{ $bankbranch=$bank_info->bank_branch}}">
                        <input type="hidden" value="{{ $bankaccountname=$bank_info->bank_account_name}}">
                        <input type="hidden" value="{{ $bankaccountnumber=$bank_info->bank_account_number}}">
                        @else
                        <input type="hidden" value="{{ $bankinfo='null'}}">
                        <input type="hidden" value="{{ $bankname='-'}}">
                        <input type="hidden" value="{{ $bankbranch='-'}}">
                        <input type="hidden" value="{{ $bankaccountname='-'}}">
                        <input type="hidden" value="{{ $bankaccountnumber='-'}}">
                        @endif
                        <input type="hidden" id="bank" value="{{$bankinfo}}">
                        <input type="hidden" id="bankname" value="{{$bankname}}">
                        <input type="hidden" id="bankbranch" value="{{$bankbranch}}">
                        <input type="hidden" id="bankaccountname" value="{{$bankaccountname}}">
                        <input type="hidden" id="bankaccountnumber" value="{{$bankaccountnumber}}">

                        <div class="encashment-holder">
                            @if(isset($member->account_encashment_type))
                            @if($member->account_encashment_type == 1)
                            <div class="form-group">
                                <div class="form-group-addon">
                            @if(isset($bank_info->bank_name))
                                <?php $bankname = $bank_info->bank_name; ?>
                            @else
                                <?php $bankname = 'RCBC'; ?>
                            @endif
                            </div>
                                <label for="email" class="col-md-2 control-label">Bank Name</label>
                                <div class="col-md-10">
                                    <select name="bankselect" class="form-control">
                                        <option value="BDO" <?php if($bankname=="BDO"){ echo "selected";} ?>>BDO</option>
                                        <option value="Metrobank" <?php if($bankname=="Metrobank"){ echo "selected";} ?>>Metrobank</option>
                                        <option value="BPI" <?php if($bankname=="BPI"){ echo "selected";} ?>>BPI</option>
                                        <option value="PNB" <?php if($bankname=="PNB"){ echo "selected";} ?>>PNB</option>
                                        <option value="Security Bank" <?php if($bankname=="Security Bank"){ echo "selected";} ?>>Security Bank</option>
                                        <option value="Chinabank" <?php if($bankname=="Chinabank"){ echo "selected";} ?>>Chinabank</option>
                                        <option value="RCBC" <?php if($bankname=="RCBC"){ echo "selected";} ?>>RCBC</option>
                                        <option value="UCPB" <?php if($bankname=="UCPB"){ echo "selected";} ?>>UCPB</option>
                                        <option value="EastWest Bank" <?php if($bankname=="EastWest Bank"){ echo "selected";} ?>>EastWest Bank</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="website" class="col-md-2 control-label">Bank Branch</label>
                                <div class="col-md-10">
                                    <input name="bank-branch" id="bank-branch" class="form-control" value="{{$bankbranch}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb" class="col-md-2 control-label">Bank Account Name</label>
                                <div class="col-md-10">
                                    <input name="bank-account-name" id="bank-account-name" class="form-control" value="{{$bankaccountname}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb" class="col-md-2 control-label">Bank Account Number</label>
                                <div class="col-md-10">
                                    <input name="bank-account-id" id="bank-account-id" class="form-control" value="{{$bankaccountnumber}}">
                                </div>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="email" class="col-md-2 control-label">Name on Cheque</label>
                                <div class="col-md-10">
                                    <input name="cheque-name" id="cheque-name" class="form-control" value="{{$chequename}}">
                                </div>
                            </div><br>
                            @endif
                            @endif 
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 pull-right text-right">
                                <button class="btn btn-success" id="updateprofile">APPLY CHANGES</button>
                            </div>
                        </div>

                        <script type="text/javascript">
                        $("#encashmentselect").change(function()
                        {
                            var cheque = $("#cheque").val();
                            var chequename = $("#chequename").val();

                            var bank = $("#bank").val();
                            var bankname = $("#bankname").val();
                            var bankbranch = $("#bankbranch").val();
                            var bankaccountname = $("#bankaccountname").val();
                            var bankaccountnumber = $("#bankaccountnumber").val();
                            
                            var encashmentvalue = $("#encashmentselect").val();
                            if(encashmentvalue == 1)
                            {
                                if(bank != null)
                                {
                                var encashmentdata = '<div class="form-group"><div class="form-group"><div class="form-group-addon"></div><label for="email" class="col-md-2 control-label">Bank Name</label><div class="col-md-10"><select name="bankselect" class="form-control"><option value="BDO" <?php if($bankname=="BDO"){ echo "selected";} ?>>BDO</option><option value="Metrobank" <?php if($bankname=="Metrobank"){ echo "selected";} ?>>Metrobank</option>option value="BPI" <?php if($bankname=="BPI"){ echo "selected";} ?>>BPI</option><option value="PNB" <?php if($bankname=="PNB"){ echo "selected";} ?>>PNB</option><option value="Security Bank" <?php if($bankname=="Security Bank"){ echo "selected";} ?>>Security Bank</option><option value="Chinabank" <?php if($bankname=="Chinabank"){ echo "selected";} ?>>Chinabank</option><option value="RCBC" <?php if($bankname=="RCBC"){ echo "selected";} ?>>RCBC</option><option value="UCPB" <?php if($bankname=="UCPB"){ echo "selected";} ?>>UCPB</option><option value="EastWest Bank" <?php if($bankname=="EastWest Bank"){ echo "selected";} ?>>EastWest Bank</option></select></div></div><div class="form-group"><label for="website" class="col-md-2 control-label">Bank Branch</label><div class="col-md-10"><input name="bank-branch" id="bank-branch" class="form-control" value="'+bankbranch+'"></div></div><div class="form-group"><label for="fb" class="col-md-2 control-label">Bank Account Name</label><div class="col-md-10"><input name="bank-account-name" id="bank-account-name" class="form-control" value="'+bankaccountname+'"></div></div><div class="form-group"><label for="fb" class="col-md-2 control-label">Bank Account Number</label><div class="col-md-10"><input name="bank-account-id" id="bank-account-id" class="form-control" value="'+bankaccountnumber+'"></div></div>'
                                }
                                else
                                {
                                    var encashmentdata = '<div class="form-group"><label for="email" class="col-md-2 control-label">Bank Name</label><div class="col-md-10"><input name="bank-name" id="bank-name" class="form-control" value="-"></div></div><div class="form-group"><label for="website" class="col-md-2 control-label">Bank Branch</label><div class="col-md-10"><input name="bank-branch" id="bank-branch" class="form-control" value="-"></div></div><div class="form-group"><label for="fb" class="col-md-2 control-label">Bank Account Name</label><div class="col-md-10"><input name="bank-account-name" id="bank-account-name" class="form-control" value="-"></div></div><div class="form-group"><label for="fb" class="col-md-2 control-label">Bank Account Number</label><div class="col-md-10"><input name="bank-account-id" id="bank-account-id" class="form-control" value="-"></div></div>'
                                }
                            }
                            else
                            {
                                if(cheque != "null")
                                {
                                var encashmentdata = '<div class="form-group"><label for="email" class="col-md-2 control-label">Name on Cheque</label><div class="col-md-10"><input name="cheque-name" id="cheque-name" class="form-control" value="'+chequename+'"></div></div><br>'
                                }
                                else
                                {
                                    var encashmentdata = '<div class="form-group"><label for="email" class="col-md-2 control-label">Name on Cheque</label><div class="col-md-10"><input name="cheque-name" id="cheque-name" class="form-control" value=""></div></div><br>'
                                }
                            }
                            $(".encashment-holder").html("");
                            $(".encashment-holder").html(encashmentdata);
                        });
                        </script>
                    </div>
                </form>
            </div>
            <!-- CHANGE PASSWORD -->
            <div class="tab-pane scrollable list-group <?php echo (!isset($_GET["tab"]) && isset($_GET["tabpw"]) && !Request::input('wallet') &&  !Request::input('gc') ? "active" : ""); ?>" id="change-password">
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
            </div>


        </div> 

    </div>

    <div class="col-md-6 col-lg-4">
        <div class="panel panel-default panel-block">
            <div class="list-group">
                <div class="list-group-item">
                    <h4 class="section-title"><img src="/assets/mlm/img/news-icon.png"> <span>NEWS & ANNOUNCEMENTS</span></h4>
                    <div class="news-container">
                        <div class="holder">
                            <div class="img">
                                <img src="/assets/mlm/img/news-sample.jpg">
                            </div>
                            <div class="title">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</div>
                            <div class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</div>
                            <div class="date">Jan. 25, 2017</div>
                        </div>
                        <div class="holder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="td-img">
                                            <div class="img">
                                                <img src="/assets/mlm/img/news-sample.jpg">
                                            </div>
                                        </td>
                                        <td class="td-text">
                                            <div class="title">Lorem ipsum dolor sit amet</div>
                                            <div class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</div>
                                            <div class="date">Jan. 25, 2017</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="holder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="td-img">
                                            <div class="img">
                                                <img src="/assets/mlm/img/news-sample.jpg">
                                            </div>
                                        </td>
                                        <td class="td-text">
                                            <div class="title">Lorem ipsum dolor sit amet</div>
                                            <div class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</div>
                                            <div class="date">Jan. 25, 2017</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="holder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="td-img">
                                            <div class="img">
                                                <img src="/assets/mlm/img/news-sample.jpg">
                                            </div>
                                        </td>
                                        <td class="td-text">
                                            <div class="title">Lorem ipsum dolor sit amet</div>
                                            <div class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</div>
                                            <div class="date">Jan. 25, 2017</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <ul class="pagination" style="margin-bottom: 0;">
                                <li>
                                    <a href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li>
                                    <a href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="/resources/assets/toaster/toastr.min.js"></script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/mlm/css/dashboard.css">
@endsection