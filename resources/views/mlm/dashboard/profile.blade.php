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