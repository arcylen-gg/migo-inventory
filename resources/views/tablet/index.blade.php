@extends('tablet.layout')
@section('content')
<div class="form-group">
    <div class="col-md-12">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="panel panel-default panel-block panel-title-block" id="top">
            <div class="panel-heading">
                <div class="col-md-6 col-xs-6">
                    <i class="fa fa-tablet"></i>
                    <h1>
                        <span class="page-title">Tablet</span>
                        <small>
                            Login as Sales Agent
                        </small>
                    </h1>
                </div>
                <div class="col-md-6 col-xs-6 text-right">
                    <div class="col-md-12">
                        <label>{{$employee_name}}</label><br>
                        <label>{{$employee_position}}</label><br>
                        <a href="/tablet/logout">Logout</a>
                    </div>
                    <div class="col-sm-4 pull-right">
                        <form class="select-sir" method="get">
                            <select class="choose-sir form-control" name="sir_id">
                                @foreach($_sirs as $sir)
                                    <option {{Session::get("sir_id") == $sir->sir_id ? 'selected' : '' }} value="{{$sir->sir_id}}">SIR #{{$sir->sir_id}}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-block panel-title-block">
            <div class="panel-body form-horizontal">
                    <!-- <div class="form-group">
                        <div class="col-md-4">
                            <form class="global-submit form-to-submit-add" action="/tablet/sync_import" method="post">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <button type="submit" class="btn btn-primary">Sync Import Data</button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form class="global-submit form-to-submit-add" action="/tablet/sync_export" method="post">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <button type="submit" class="btn btn-primary">Sync Export Data</button>
                            </form>
                        </div>
                    </div> -->
                <div class="form-group tab-content panel-body sir_container">
                    <div class="tab-pane fade in active">
                        <div class="form-group order-tags">
                            <div class="col-md-12 text-center">
                              @if(isset($no_sir))
                                @if($no_sir == "no_sir")
                                <div class="form-group">
                                  <h2>You don't have any Load Out Form yet.</h2>
                                </div>
                                @else
                                <div class="form-group">
                                  <h2>You don't have any Load Out Form yet.</h2>
                                </div>
                                @endif
                              @elseif($sir != '')
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <h3>Load Out Form No: <strong>{{sprintf("%'.05d\n", Session::get("sir_id"))}}</strong></h3>
                                        <ul class="nav nav-tabs">
                                          <li id="all-list" class="active">
                                            <a data-toggle="tab" onclick="select_list('all')"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;All List</a>
                                          </li>
                                          <li id="checked">
                                            <a data-toggle="tab" onclick="select_list('checked')"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;Checked</a>
                                            </li>
                                          <li id="unchecked">
                                            <a data-toggle="tab" onclick="select_list('unchecked')"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp;Unchecked</a>
                                          </li>
                                        </ul>
                                    </div>               
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 col-xs-6">
                                        <select class="form-control drop-down-category">
                                            @include("member.load_ajax_data.load_category", ['add_search' => ""])
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-xs-6">
                                        <div class="input-group">
                                            <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                                            <input type="text" class="form-control search_name" onkeyup="doSearch();" id="search_txt" placeholder="Search by product name" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                            
                                    <div class="col-md-12"> 
                                        <div class="load-data" target="sir_item" filter="active" filteru="anime" >
                                            <div id="sir_item">     
                                                <div class="table-responsive">
                                                    <table id="item_table" class="table table-bordered table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Product Code</th>
                                                                <th>Product Name</th>
                                                                <th>Qty</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($_sir_item as $item)
                                                                <tr class="text-left unchecked all_tr tr_{{$item->sir_item_id}}">
                                                                    <td><input type="checkbox" name="" value="{{$item->sir_item_id}}" onclick="checked_item({{$item->sir_item_id}})"></td>
                                                                    <td>{{$item->item_barcode}}</td>
                                                                    <td>{{$item->item_name}} ({{$item->type_name}})</td>
                                                                    <td>{{$item->qty}}</td>
                                                                </tr>
                                                            @endforeach
                                                            <tr id="noresults"><span id="qt"></span></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-xs-6">
                                        <a link="/tablet/pis/sir/{{Session::get('sir_id')}}/confirm" size="md" class="popup btn btn-primary form-control">Confirm</a>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <a link="/tablet/pis/sir/{{Session::get('sir_id')}}/reject" size="md" class="popup btn btn-primary form-control">Reject</a>
                                    </div>
                                </div>
                              @else
                              <h2>You don't have any Load Out Form yet.</h2>
                              @endif  
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
<script type="text/javascript">

$('body').on("change", ".choose-sir", function()
{
   $('.select-sir').submit();
}); 
    function submit_done(data)
    {
        if(data.status == "success")
        {
            toastr.success("Success");
            location.href = "/tablet/dashboard";
        }
        else if(data.status == "error")
        {
            toastr.warning(data.status_message);
            $(data.target).html(data.view);
        }
    }
    function select_list(str = '')
    {
        if(str == "checked")
        {
            $(".checked").removeClass("hidden");
            $(".unchecked").addClass("hidden");
        }
        else if(str == "unchecked")
        {
            $(".checked").addClass("hidden");
            $(".unchecked").removeClass("hidden");
        }
        else
        {
            $(".all_tr").removeClass("hidden");
        }
    }
    function checked_item(tr_id)
    {
        $(".tr_"+tr_id).toggleClass("checked");
        $(".tr_"+tr_id).toggleClass("unchecked");
    }
    var category = "";
    $(".drop-down-category").globalDropList(
    {
        hasPopup    : 'false',
        width       : '100%',
        onChangeValue: function()
        {
           category = $(this).find("option:selected").attr("type-name");
           searchTable(category);
        }
    });
    $('#search_txt').keyup(function()
    {
        searchTable($(this).val());
        if($(this).val() == "")
        {            
           searchTable(category);
        }
    });
    function searchTable(inputVal)
    {
        var table = $('#item_table');
        table.find('tr').each(function(index, row)
        {
            var allCells = $(row).find('td');
            if(allCells.length > 0)
            {
                var found = false;
                allCells.each(function(index, td)
                {
                    var regExp = new RegExp(inputVal, 'i');
                    if(regExp.test($(td).text()))
                    {
                        found = true;
                        return false;
                    }
                });
                if(found == true)$(row).show();else $(row).hide();
            }
        });
    }
</script>
@endsection