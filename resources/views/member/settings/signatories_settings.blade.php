@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action or ''}}" method="post">
    <div class="panel panel-default panel-block panel-title-block">
        <input type="hidden" class="button-action" name="button_action" value="">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
        <div class="panel-heading">
            <div>
                <i class="fa fa-calendar"></i>
                <h1>
                <span class="page-title">{{$page or ''}}</span>
                <small>
                </small>
                </h1>
                <div class="dropdown pull-right">
                   <button class="btn btn-primary">Save Settings</button>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
        <div class="data-container" >
            <div class="tab-content">
                <div class="row row-set-signatories">  
                    <div class="col-md-12 li-set-signatories"  style="padding: 30px;">
                        @if(count($transaction) > 0)
                            @foreach($transaction as $key => $trans)
                            <div style="padding: 15px;border: 1px solid #ddd;  margin-bottom: 10px;" class="li-signatories-name-{{$key}}">
                                <div class="row clearfix"><div class="col-md-12"><strong> SET <span class="set-number-div">1</span> </strong></div></div>
                                <div class="row clearfix">
                                    <div class="col-md-6 signatories-li">
                                        <div class="signatories-div" naming="0" id="transaction_number_1">
                                            @foreach($trans['keyname'] as $keyname => $valuename)
                                            <div class="row-signatories">
                                                <div class="col-md-5">
                                                    <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[{{$key}}][]" value="3">
                                                    <label>Position</label>
                                                    <input type="text" class="form-control" naming="settings_key" name="settings_key[{{$key}}][]" value="{{$valuename}}">
                                                </div>
                                                <div class="col-md-5">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" naming="settings_value" name="settings_value[{{$key}}][]" value="{{$trans['keyvalue'][$keyname]}}">
                                                </div>
                                                <div class="col-md-1">
                                                    <label>&nbsp</label>
                                                    <button type="button" naming="btn_add_line" class="btn btn-primary button-add-name btn-add-line" btn_num="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>&nbsp</label>
                                                    <button type="button" naming="btn_remove_line" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            @endforeach                                            
                                        </div>
                                    </div>
                                    <div class="col-md-4 transaction-li">
                                        <h4>Transaction</h4>
                                        <div class="vendor">
                                            <label><strong>Vendor</strong></label><br>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" {{check_ifexist('pr',$trans['transaction'])}} name="transaction[{{$key}}][]"  class="checkbox-check pr" naming="pr" value="pr">PR
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('po',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check po" naming="po" value="po">PO
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('ri',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check ri" naming="ri" value="ri">RI
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('eb',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check eb" naming="eb" value="eb">EB
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('pb',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check pb" naming="pb" value="pb">PB
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('dm',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check dm" naming="dm" value="dm">DM
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('wc',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check wc" naming="wc" value="wc">WC
                                            </label>
                                        </div>
                                        <div class="customer">
                                            <label><strong>Customer</strong></label><br>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('eq',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check eq" naming="eq" value="eq">EQ
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('so',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check so" naming="so" value="so">SO
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('si',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check si" naming="si" value="si">SI
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('sr',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check sr" naming="sr" value="sr">SR
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox"  {{check_ifexist('wisdr',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check wisdr" naming="wisdr" value="wisdr">WIS/DR
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" {{check_ifexist('rp',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check rp" naming="rp" value="rp">RP
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" {{check_ifexist('cm',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check cm" naming="cm" value="cm">CM
                                            </label>
                                        </div>
                                        <div class="warehouse">
                                            <label><strong>Warehouse</strong></label><br>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" {{check_ifexist('wtrr',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check wtrr" naming="wtrr" value="wtrr">WT/RR
                                            </label>
                                            <label class="checkbox-inline">
                                              <input type="checkbox" {{check_ifexist('adj',$trans['transaction'])}} name="transaction[{{$key}}][]" class="checkbox-check adj" naming="adj" value="adj">ADJ
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <div class="col-md-6">
                                            <label>&nbsp</label>
                                            <button type="button" class="btn btn-primary btn-set-add-line"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="col-md-6">
                                            <label>&nbsp</label>
                                            <button type="button" class="btn btn-danger btn-set-remove-line"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div style="padding: 15px;border: 1px solid #ddd;  margin-bottom: 10px;" class="li-signatories-name-1">
                            <div class="row clearfix"><div class="col-md-12"><strong> SET <span class="set-number-div">1</span> </strong></div></div>
                            <div class="row clearfix">
                                <div class="col-md-6 signatories-li">
                                    <div class="signatories-div" naming="0" id="transaction_number_1">
                                        <div class="row-signatories">
                                            <div class="col-md-5">
                                                <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[0][]" value="3">
                                                <label>Position</label>
                                                <input type="text" class="form-control" naming="settings_key" name="settings_key[0][]">
                                            </div>
                                            <div class="col-md-5">
                                                <label>Name</label>
                                                <input type="text" class="form-control" naming="settings_value" name="settings_value[0][]">
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp</label>
                                                <button type="button" naming="btn_add_line" class="btn btn-primary button-add-name btn-add-line" btn_num="0"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp</label>
                                                <button type="button" naming="btn_remove_line" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="row-signatories">
                                            <div class="col-md-5">
                                                <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[0][]" value="3">
                                                <label>Position</label>
                                                <input type="text" class="form-control" naming="settings_key" name="settings_key[0][]">
                                            </div>
                                            <div class="col-md-5">
                                                <label>Name</label>
                                                <input type="text" class="form-control" naming="settings_value" name="settings_value[0][]">
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp</label>
                                                <button type="button" naming="btn_add_line" class="btn btn-primary button-add-name btn-add-line"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp</label>
                                                <button type="button" naming="btn_remove_line" btn_num="0" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="row-signatories">
                                            <div class="col-md-5">
                                                <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[0][]" value="3">
                                                <label>Position</label>
                                                <input type="text" class="form-control" naming="settings_key" name="settings_key[0][]">
                                            </div>
                                            <div class="col-md-5">
                                                <label>Name</label>
                                                <input type="text" class="form-control" naming="settings_value" name="settings_value[0][]">
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp</label>
                                                <button type="button" naming="btn_add_line" btn_num="0" class="btn btn-primary button-add-name btn-add-line"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp</label>
                                                <button type="button" naming="btn_remove_line" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 transaction-li">
                                    <h4>Transaction</h4>
                                    <div class="vendor">
                                        <label><strong>Vendor</strong></label><br>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check pr" naming="pr" value="pr">PR
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check po" naming="po" value="po">PO
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check ri" naming="ri" value="ri">RI
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check eb" naming="eb" value="eb">EB
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check pb" naming="pb" value="pb">PB
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check dm" naming="dm" value="dm">DM
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check wc" naming="wc" value="wc">WC
                                        </label>
                                    </div>
                                    <div class="customer">
                                        <label><strong>Customer</strong></label><br>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check eq" naming="eq" value="eq">EQ
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check so" naming="so" value="so">SO
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check si" naming="si" value="si">SI
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check sr" naming="sr" value="sr">SR
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check wisdr" naming="wisdr" value="wisdr">WIS/DR
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check rp" naming="rp" value="rp">RP
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check cm" naming="cm" value="cm">CM
                                        </label>
                                    </div>
                                    <div class="warehouse">
                                        <label><strong>Warehouse</strong></label><br>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check wtrr" naming="wtrr" value="wtrr">WT/RR
                                        </label>
                                        <label class="checkbox-inline">
                                          <input type="checkbox" name="transaction[0][]" class="checkbox-check adj" naming="adj" value="adj">ADJ
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <div class="col-md-6">
                                        <label>&nbsp</label>
                                        <button type="button" class="btn btn-primary btn-set-add-line"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp</label>
                                        <button type="button" class="btn btn-danger btn-set-remove-line"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="div-script-set">
    <div class="body-script-set hidden">
        <div style="padding: 15px;border: 1px solid #ddd;  margin-bottom: 10px;">
            <div class="row clearfix"> <div class="col-md-12"><strong> SET <span class="set-number-div">2</span> </strong></div></div>
            <div class="row clearfix set-names-li">
                <div class="col-md-6 signatories-li">
                    <div class="settings signatories-div" id="transaction_number">
                        <div class="row-signatories">
                            <div class="col-md-5">
                                <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[]" value="3">
                                <label>Position</label>
                                <input type="text" class="form-control" naming="settings_key" name="settings_key[]">
                            </div>
                            <div class="col-md-5">
                                <label>Name</label>
                                <input type="text" class="form-control" naming="settings_value" name="settings_value[]">
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp</label>
                                <button type="button" naming="btn_add_line" class="btn btn-primary button-add-name btn-add-line"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp</label>
                                <button type="button" naming="btn_remove_line" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row-signatories">
                            <div class="col-md-5">
                                <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[]" value="3">
                                <label>Position</label>
                                <input type="text" class="form-control" naming="settings_key" name="settings_key[]">
                            </div>
                            <div class="col-md-5">
                                <label>Name</label>
                                <input type="text" class="form-control" naming="settings_value" name="settings_value[]">
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp</label>
                                <button type="button" naming="btn_add_line" class="btn btn-primary button-add-name btn-add-line"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp</label>
                                <button type="button" naming="btn_remove_line" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row-signatories">
                            <div class="col-md-5">
                                <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[]" value="3">
                                <label>Position</label>
                                <input type="text" class="form-control" naming="settings_key" name="settings_key[]">
                            </div>
                            <div class="col-md-5">
                                <label>Name</label>
                                <input type="text" class="form-control" naming="settings_value" name="settings_value[]">
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp</label>
                                <button type="button" naming="btn_add_line" class="btn btn-primary button-add-name btn-add-line"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp</label>
                                <button type="button" naming="btn_remove_line" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 transaction-li">
                    <h4>Transaction</h4>
                    <div class="vendor">
                        <label><strong>Vendor</strong></label><br>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check pr" naming="pr" value="pr">PR
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check po" naming="po" value="po">PO
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check ri" naming="ri" value="ri">RI
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check eb" naming="eb" value="eb">EB
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check pb" naming="pb" value="pb">PB
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check dm" naming="dm" value="dm">DM
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check wc" naming="wc" value="wc">WC
                        </label>
                    </div>
                    <div class="customer">
                        <label><strong>Customer</strong></label><br>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check eq" naming="eq" value="eq">EQ
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check so" naming="so" value="so">SO
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check si" naming="si" value="si">SI
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check sr" naming="sr" value="sr">SR
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check wisdr" naming="wisdr" value="wisdr">WIS/DR
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check rp" naming="rp" value="rp">RP
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check cm" naming="cm" value="cm">CM
                        </label>
                    </div>
                    <div class="warehouse">
                        <label><strong>Warehouse</strong></label><br>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check wtrr" naming="wtrr" value="wtrr">WT/RR
                        </label>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="transaction[]" class="checkbox-check adj" naming="adj" value="adj">ADJ
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="col-md-6">
                        <label>&nbsp</label>
                        <button type="button" class="btn btn-primary btn-set-add-line"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="col-md-6">
                        <label>&nbsp</label>
                        <button type="button" class="btn btn-danger btn-set-remove-line"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="div-script">
    <div class="body-script hidden">
        <div class="row-signatories">
            <div class="col-md-5">
                <input type="hidden" class="form-control" naming="settings_setup_done" name="settings_setup_done[]" value="3">
                <label>Position</label>
                <input type="text" class="form-control" naming="settings_key" name="settings_key[]">
            </div>
            <div class="col-md-5">
                <label>Name</label>
                <input type="text" class="form-control" naming="settings_value" name="settings_value[]">
            </div>
            <div class="col-md-1">
                <label>&nbsp</label>
                <button type="button" class="btn btn-primary button-add-name btn-add-line"><i class="fa fa-plus"></i></button>
            </div>
            <div class="col-md-1">
                <label>&nbsp</label>
                <button type="button" class="btn btn-danger btn-remove-line"><i class="fa fa-times"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection
@section("script")
<script type="text/javascript">
    $("body").on("click",".btn-set-add-line", function()
    {
        $(".li-set-signatories").append($(".div-script-set .body-script-set").html());
        action_reassign_number();
    });

    $("body").on("click",".btn-set-remove-line", function()
    {       
        var len = $(".li-set-signatories .btn-set-remove-line").length;
        if($(".li-set-signatories .btn-set-remove-line").length > 1)
        {
            $(this).parent().parent().parent().parent().remove();
            action_reassign_number();
        }
    });

    function action_reassign_number()
    {
        var num = 1;
        $(".set-number-div").each(function()
        {
            $(this).html(num);
            $parent = $(this).parent().parent().parent().parent();
            $parent.removeClass();
            $parent.addClass("li-signatories-name-"+ num);
            $parent.attr("num-row", num);
            $transaction_li = $parent.find(".col-md-4.transaction-li");
            $transaction_li.find("input[type=checkbox]").attr("name","transaction["+num+"][]");

            $signatories_li = $parent.find(".col-md-6.signatories-li");
            $signatories_li.find("input[naming=settings_setup_done]").attr("name","settings_setup_done["+num+"][]");
            $signatories_li.find("input[naming=settings_key]").attr("name","settings_key["+num+"][]");
            $signatories_li.find("input[naming=settings_value]").attr("name","settings_value["+num+"][]");

            num++;
        });
    }
    $("body").on("click",".btn-add-line", function()
    {
        $parents = $(this).parent().parent().parent();
        $parents.append($(".div-script .body-script").html());
        action_reassign_number();        
    });
    $("body").on("click",".btn-remove-line", function()
    {
        var len = $(this).parent().parent().parent().find(".row-signatories").length;
        if(len > 1)
        {
            $(this).parent().parent().remove();
            action_reassign_number();
        }
    });
    $("body").on("click",".checkbox-check", function()
    {
        $trans = $(this).attr("naming");
        if($(this).prop("checked"))
        {
            $("input[naming="+$trans+"]").prop("checked", false);
            $("input[naming="+$trans+"]").attr("disabled", true);
            $(this).attr("disabled", false);
            $(this).prop("checked", true);
        }
        else
        {
            $("input[naming="+$trans+"]").prop("checked", false);
            $("input[naming="+$trans+"]").attr("disabled", false);
        }
    });
    function success_signatories(data)
    {
        if(data.status == 'success')
        {
            toastr.success("Success");
            location.reload();
        }
    }
</script>
@endsection