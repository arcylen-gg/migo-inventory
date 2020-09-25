@extends('member.layout')
@section('content')
<form class="global-submit" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="je_id" value="{{Request::input('id')}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Create Journal Entry</span>
                    <small>
                    
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Save</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Save and New</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-close">Save and Close</button>
                <a class="panel-buttons btn btn-custom-white pull-right" href="/member/accounting/journal/list">Close</a>
                @if(isset($inv))
                <div class="pull-right">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="/member/accounting/journal/entry/invoice/{{$inv->inv_id}}">Transaction Journal</a></li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray load-data">
        <div class="data-container" >
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-12" style="padding: 30px;">
                        <!-- START CONTENT -->
                        <div style="padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                            </div>
                        </div>
                        <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                                <div class="col-sm-2">
                                    <label>Date</label>
                                    <input type="text" class="datepicker form-control input-sm" name="je_entry_date" value="{{isset($journal->je_entry_date) ? dateFormat($journal->je_entry_date) : date('m/d/Y')}}" />
                                </div>
                                <!-- <div class="col-sm-4 offset-sm-6">    
                                    <label>Journal No:</label>
                                    <input type="text" class="form-control input-sm" name="" value="">
                                </div> -->
                            </div>
                        </div>
                        
                        <div class="row clearfix draggable-container">
                            <div class="table-responsive">
                                <div class="col-sm-12">
                                    <table class="digima-table">
                                        <thead>
                                            <tr>
                                                <th style="" class="text-right">#</th>
                                                <th style="">Account</th>
                                                <th style="">Debits</th>
                                                <th style="">Credits</th>
                                                <th style="">Description</th>
                                                <th style="">Name</th>
                                                <th style=""></th>
                                            </tr>
                                        </thead>
                                        <tbody class="draggable tbody-item">     
                                            @if(isset($journal->line))
                                                @foreach($journal->line as $jline)
                                                    <tr class="tr-draggable">
                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td>
                                                        <select class="form-control select-coa drop-down-coa input-sm" name="jline_account_id[]" >
                                                            @include("member.load_ajax_data.load_chart_account", ['add_search' => "", 'account_id' => $jline->jline_account_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="text-right money-format compute debit-amount" type="text" name="jline_debit[]" value="{{$jline->jline_type == 'Debit' ? currency('',$jline->jline_amount) : ''}}"/>
                                                    </td>
                                                    <td>
                                                        <input class="text-right money-format compute credit-amount" type="text" name="jline_credit[]" value="{{$jline->jline_type == 'Credit' ? currency('',$jline->jline_amount) : ''}}"/>
                                                    </td>
                                                    <td>
                                                        <textarea class="textarea-expand" type="text" name="jline_description[]" >{{$jline->jline_description}}</textarea>
                                                    </td>
                                                    <td>
                                                        <select class="form-control select-name drop-down-name input-sm pull-left" name="jline_name_id[]">
                                                            @include("member.load_ajax_data.load_name", ['name_id'=>$jline->jline_name_id, 'ref_name'=>$jline->jline_name_reference])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                        <input type="hidden" class="reference_name" name="jline_name_reference[]" value="{{$jline->jline_name_reference}}">
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                                @endforeach
                                            @else                                
                                                <tr class="tr-draggable">
                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td>
                                                        <select class="form-control select-coa drop-down-coa input-sm" name="jline_account_id[]" >
                                                            @include("member.load_ajax_data.load_chart_account", ['add_search' => ""])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="text-right money-format compute debit-amount" type="text" name="jline_debit[]"/>
                                                    </td>
                                                    <td>
                                                        <input class="text-right money-format compute credit-amount" type="text" name="jline_credit[]"/>
                                                    </td>
                                                    <td>
                                                        <textarea class="textarea-expand" type="text" name="jline_description[]" ></textarea>
                                                    </td>
                                                    <td>
                                                        <select class="form-control select-name drop-down-name input-sm pull-left" name="jline_name_id[]">
                                                            @include("member.load_ajax_data.load_name")
                                                            <option class="hidden" value="" />
                                                        </select>
                                                        <input type="hidden" class="reference_name" name="jline_name_reference[]">
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tbody class="">  
                                            <tr class="gray">
                                                <td></td>
                                                <td></td>
                                                <td class="debit-total text-right">0.00</td>
                                                <td class="credit-total text-right">0.00</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Statement Memo</label>
                                <textarea class="form-control input-sm textarea-expand" name="inv_memo" placeholder=""></textarea>
                            </div>
                        </div>
                        <!-- END CONTENT -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="invoice-number-td text-right">1</td>
            <td>
                <select class="form-control select-coa drop-down-coa input-sm" name="jline_account_id[]" >
                    @include("member.load_ajax_data.load_chart_account", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td><input class="text-right money-format compute debit-amount" type="text" name="jline_debit[]"/></td>
            <td><input class="text-right money-format compute credit-amount" type="text" name="jline_credit[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="jline_description[]" ></textarea></td>
            <td>
                <select class="form-control select-name input-sm pull-left" name="jline_name_id[]" >
                    @include("member.load_ajax_data.load_name")
                    <option class="hidden" value="" />
                </select>
                <input type="hidden" class="reference_name" name="jline_name_reference[]">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection


@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript">
@if(Session::has('success'))
    toastr.success('{{Session::get('success')}}');
@endif

var global_tr_html = $(".div-script tbody").html();
var manual_journal = new manual_journal();

function manual_journal()
{
    init();

    function init()
    {
        document_ready();
    }

    function document_ready()
    {
        event_remove_tr();
        event_compute_class_change();
        event_debit_credit_change_only_one_accept();
        event_button_action_click();
        event_droplist_input_change();

        action_initialize_select();
        action_lastclick_row();
        action_compute();
    }

    function action_initialize_select()
    {
        $(".drop-down-coa").globalDropList(
        {
            width       : '100%',
            link        : '/member/accounting/chart_of_account/popup/add',
            link_size   : 'md',
            placeholder : 'Chart of Account',
            onCreateNew : function()
            {
                account_selected = $(this);
            },
            onChangeValue: function()
            {
                $(this).find("tr").find(".select-name").change();
            }
        });

        $(".drop-down-name").globalDropList(
        {
            width       : '100%',
            hasPopup    : 'false',
            placeholder : 'Customers or Vendor',
            onChangeValue: function(e)
            {
                /* SET REFERENCE TYPE (CUSTOMER OR VENDOR) */
                $(this).parents("td").find(".reference_name").val($(this).find("option:selected").attr("reference"));

            }
        });
    }

    function event_droplist_input_change()
    {
        $(document).on("change", "input", function()
        {
            if($(this).parents(".droplist").find("select").hasClass("select-name"))
            {
                $name_reference     = $(this).parents("tr").find(".select-name").find("option:selected").attr("reference");
                $account_reference  = $(this).parents("tr").find(".select-coa").find("option:selected").attr("reference");

                if($name_reference != '' && $name_reference != $account_reference)
                {
                    this.setCustomValidity("Invaid name type for account type");
                    return false;
                }
                else
                {   
                    this.setCustomValidity("");
                    return true;
                }
            }
            else if($(this).parents(".droplist").find("select").hasClass("select-coa"))
            {
                
            }
        })
    }

    function event_remove_tr()
    {
        $(document).on("click", ".remove-tr", function(e){
            if($(".tbody-item .remove-tr").length > 1){
                $(this).parent().remove();
                action_reassign_number();
                action_compute();
            }           
        });
    }

    function event_compute_class_change()
    {
        $(document).on("change",".compute", function()
        {
            action_compute();
        });
    }

    function event_debit_credit_change_only_one_accept()
    {
        $(document).on("change",".debit-amount", function()
        {
            $(this).parents("tr").find(".credit-amount").val("");
        })

        $(document).on("change",".credit-amount", function()
        {
            $(this).parents("tr").find(".debit-amount").val("");
        })
    }

    function action_compute()
    {
        var debit_total = 0;
        var credit_total = 0;

        $(".debit-amount").each(function()
        {
            debit_total += formatFloat($(this).val());
        })

        $(".credit-amount").each(function()
        {
            credit_total += formatFloat($(this).val());
        })

        $(".credit-total").html(formatMoney(credit_total));
        $(".debit-total").html(formatMoney(debit_total));
    }

    function formatFloat($this) // Bryan Kier
    {
        return Number($this.toString().replace(/[^0-9\.]+/g,""));
    }

     function formatMoney($this) // Bryan Kier
    {
        if($this != '')
        {
            var n = formatFloat($this), 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
            j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        }
    }

    function action_lastclick_row()
    {
        $(document).on("click", "tbody.draggable tr:last td:not(.remove-tr)", function(){
            action_lastclick_row_op();
        });
    }

    function action_lastclick_row_op()
    {
        $("tbody.draggable").append(global_tr_html);
        action_reassign_number();
        action_trigger_select_plugin();
        action_date_picker();
    }

    function action_trigger_select_plugin()
    {
        $(".draggable .tr-draggable:last td select.select-coa").globalDropList(
        {
            width       : '100%',
            link        : '/member/accounting/chart_of_account/popup/add',
            link_size   : 'md',
            placeholder : 'Chart of Account',
            onCreateNew : function()
            {
                account_selected = $(this);
            }
        });

        $(".draggable .tr-draggable:last td select.select-name").globalDropList(
        {
            width       : '100%',
            hasPopup    : 'false',
            placeholder : 'Customer or Vendor',
            onChangeValue: function()
            {
                $(this).parents("td").find(".reference_name").val($(this).find("option:selected").attr("reference"));
            }
        });
    }

    function action_reassign_number()
    {
        var num = 1;
        $(".invoice-number-td").each(function(){
            $(this).html(num);
            num++;
        });

        var num2 = 1;
        $(".cm-number-td").each(function(){
            $(this).html(num2);
            num2++;
        });
    }

    function action_date_picker()
    {
        $(".draggable .for-datepicker").datepicker({ dateFormat: 'mm-dd-yy', });
    }

    function event_button_action_click()
    {
        $(document).on("click","button[type='submit']", function()
        {
            $(".button-action").val($(this).attr("data-action"));
        })
    }
}

function submit_done(data)
{
    if(data.status == 'success')
    {
        if(data.redirect)
        {
            toastr.success("Success");
            location.href = data.redirect;
        }
    }
    else
    {
        toastr.error(data.message);
    }
}
</script>
@endsection