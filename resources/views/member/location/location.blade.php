
@extends('member.layout')
<style type="text/css">
    tbody tr.active
    {
        color: #fff!important;
    }
</style>
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Location &raquo; List </span>
                <small>
                    List of Location
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray ">
     <div class="panel-body">
        <div class="tab-content col-md-4">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control global-search-custom" var-name="search_province" url="" data-value="1" placeholder="Press Enter to Search" aria-describedby="basic-addon1">
            </div>
            <div id="active" class="tab-pane fade in active">
                <div class="load-data" target="province_location">
                    <div id="province_location">
                         @include('member.location.load_location_tbl', ['_location' => $_province, 'title' => "PROVINCE"])
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content col-md-4">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control global-search-custom" var-name="search_city" url="" data-value="1" placeholder="Press Enter to Search" aria-describedby="basic-addon1">
            </div>
            <div id="active" class="tab-pane fade in active">
                <div class="load-data" target="city_location">
                    <div id="city_location">
                         @include('member.location.load_location_tbl', ['_location' => $_city, 'title' => "MUNICIPALITY/CITY", 'var_name' => 'city_parent', 'parent_id' => $parent_city, 'parent_name' => $parent_city_name])
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content col-md-4">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control global-search-custom" var-name="search_barangay" url="" data-value="1" placeholder="Press Enter to Search" aria-describedby="basic-addon1">
            </div>
            <div id="active" class="tab-pane fade in active">
                <div class="load-data" target="barangay_location">
                    <div id="barangay_location">
                         @include('member.location.load_location_tbl', ['_location' => $_barangay, 'title' => "BARANGAY", 'var_name' => 'barangay_parent', 'parent_id' => $parent_barangay, 'parent_name' => $parent_barangay_name])
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
<script>
var coupon_code = new coupon_code();

function coupon_code()
{
    init();
    function init()
    {
        document_ready();
    }

    function document_ready()
    {
        action_global_search_custom();
        event_province_click();
        event_city_click();
    }

    function action_global_search_custom() // Bryan Kier
    {
        $(document).on("change", ".global-search-custom", function(e)
        {
            e.preventDefault();
            var url     = $(this).attr("url");
            var value   = $(this).val().replace(/ /g, "%20");
            var var_name= $(this).attr("var-name");

            var parent_var_name = $(this).closest(".tab-content").find(".location-parent-id").attr("var-name")
            var parent_id       = $(this).closest(".tab-content").find(".location-parent-id").html();

            $load_content =  $(this).closest(".tab-content").find(".load-data");

            $($load_content).load(url+"?"+var_name+"="+value+"&"+parent_var_name+"="+parent_id+" #"+$load_content.attr("target"));
        })
    }

    function event_province_click()
    {
        $(document).on("click", "#province_location .location-data:not('span')", function(e)
        {
            var id = $(this).closest("tr").find(".location-id").html();
            console.log($(this));
            $(".load-data[target='city_location']").find("tbody tr").html("<td>Loading...</td>");
            $(".load-data[target='barangay_location']").find("tbody tr").html("<td>Loading...</td>");

            $(".load-data[target='city_location']").load("/member/maintenance/location/list?city_parent="+id +" #city_location");
            $(".load-data[target='barangay_location']").load("/member/maintenance/location/list?city_parent="+id +" #barangay_location");

            // $(this).closest("tbody").find("tr").removeClass("active");
            // $(this).closest("tr").addClass("active");

        });
    }

    function event_city_click()
    {
        $(document).on("click", "#city_location .location-data:not('span')", function(e)
        {
            var id = $(this).closest("tr").find(".location-id").html();
            $(".load-data[target='barangay_location']").find("tbody tr").html("<td>Loading...</td>");
            $(".load-data[target='barangay_location']").load("/member/maintenance/location/list?barangay_parent="+id +" #barangay_location");

            // $(this).closest("tbody").find("tr").removeClass("active");
            // $(this).closest("tr").addClass("active");

        });
    }
}

function submit_done(data)
{
    if(data.status == "success")
    {
        data.element.modal("toggle");

        reload_content("#province_location");
        reload_content("#city_location");
        reload_content("#barangay_location");
        toastr.success(data.message);
    }
    else
    {
        toastr.error(data.message);
    }
}

function reload_content(element)
{
    var parent_var_name = $(element).closest(".tab-content").find(".location-parent-id").attr("var-name")
    var parent_id       = $(element).closest(".tab-content").find(".location-parent-id").html();

    $load_content =  $(element).closest(".tab-content").find(".load-data");

    $($load_content).load("?"+parent_var_name+"="+parent_id+" #"+$load_content.attr("target"));
}


</script>
@endsection