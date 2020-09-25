<style type="text/css">
    .table
    {
        width: inherit;
        min-width: 650px;
        margin: auto;
    }
    
    .report-container
    {
        text-align: -webkit-center;
    }
    .wrapper1, .wrapper2 { width: 100%; overflow-x: scroll; overflow-y: hidden; }
    .wrapper1 { height: 20px; }
    .div1 { height: 20px; }
    .div2 {
        position: relative;
        margin:0 auto;
        line-height: 1.4em;
    }
    .panel-report
    {
        display: inline-block;
        width: 100%;
        overflow-x: scroll;
    }
</style>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="{{$head_icon}}"></i>
            <h1>
                <span class="page-title">{{$head_title}}</span>
                <small>
                {{$head_discription}}
                </small>
            </h1>
        </div>
    </div>
</div>
<script type="text/javascript" src="/assets/mlm/jquery.aCollapTable.min.js"></script>  
<script type="text/javascript">
    function action_collaptible(collapse = false)
    {
        $('.collaptable').aCollapTable(
        { 
            startCollapsed: collapse,
            addColumn: false, 
            plusButton: '&#9658; ', 
            minusButton: '&#9660; '
        });
        if(collapse)    $(".act-more").closest("tr").find(".total-report").removeClass("hide");
        else            $(".act-more").closest("tr").find(".total-report").addClass("hide");
    }

    $(document).on("click", ".act-more", function()
    {
        $parent_tr = $(this).closest("tr");

        if($parent_tr.hasClass("act-tr-expanded"))
        {
            $parent_tr.find(".total-report").addClass("hide");
        }
        else
        {
            $parent_tr.find(".total-report").removeClass("hide");
        }
    });
    $(function () {
        $('.wrapper1').on('scroll', function (e) {
            $('.wrapper2').scrollLeft($('.wrapper1').scrollLeft());
        }); 
        $('.wrapper2').on('scroll', function (e) {
            $('.wrapper1').scrollLeft($('.wrapper2').scrollLeft());
        });
    });
    $(window).on('load', function (e) {
        $('.div1').width(1200);
        $('.div2').width(1150);
    });
</script>