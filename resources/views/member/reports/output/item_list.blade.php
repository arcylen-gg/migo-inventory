<div class="wrapper-top-scroll">
    <div class="div-top-scroll">
    </div>
</div>
<div class="wrapper-bottom-scroll">
    <div class="div-bottom-scroll">
        <div class="report-container">
            <div class="wrapper1"><div class="div1"></div></div>
            <div class="wrapper2">
                <div class="div2 table-reponsive">
                    <div class="panel panel-default panel-block panel-title-block panel-reportss load-data" style=";width: 100%">
                        <div class="panel-heading load-content">
                            @include('member.reports.report_header')
                                <div>
                                    <table class="table table-fixed tablehere scroll" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%">Item Details</th>
                                                <th style="width: 10%">Price</th>
                                                <th style="width: 10%"class="{{$w_type != 'branches' ? '' : 'hidden'}}" >Cost</th>
                                                @foreach($_warehouse as $key => $warehouse)
                                                <th style="width: 10%">{{$warehouse->warehouse_name}}</th>
                                                @endforeach
                                                <th >Pending For Transit</th>
                                                <th >In Transit</th>
                                                <th >Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbodyhere" style="overflow-y: auto !important;overflow-x: hidden !important;">
                                            @foreach($_item as $key=>$item)
                                            <tr>
                                                <td>
                                                    <b>{{$item->item_name}} </b><br>
                                                </td>
                                                <td>{{currency('',$item->item_price)}}</td>
                                                <td class=" {{$w_type != 'branches' ? '' : 'hidden'}}" >{{currency('',$item->item_cost)}}</td>
                                                @foreach($item->item_warehouse as $key=>$item_wh)
                                                <td class="text-center">{{$item_wh->qty_on_hand}}</td>
                                                @endforeach
                                                @if(count($item->item_warehouse))
                                                <td class="text-center" >{{$item->pending_transit}}</td>
                                                <td class="text-center" ><a link="/member/transaction/wis/in-transit?d={{$item->item_id}}&from={{$from}}&to={{$to}}" size="lg" class="popup">{{$item->in_transit}}</a></td>
                                                <td class="text-left" >{{collect($item->item_warehouse)->sum('qty_on_hand') + ($item->pending_transit + $item->in_transit)}}</td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <h5 class="text-center">---- {{$now or ''}} ----</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">

/*#div2
{
    width: 100%;
    right: -17px;
    overflow-y: scroll;
    overflow-x: hidden;
    background: transparent; 
}*/
.wrapper-top-scroll
{
width: 100%; border: none 0px RED;
overflow-x: scroll; /*overflow-y:hidden;*/
margin: 0 auto;
}
.wrapper-bottom-scroll
{
width: 100%; border: none 0px RED;
overflow-x: scroll; /*overflow-y:hidden;*/
margin: 0 auto;
/*height: 100%; */
}
.div-top-scroll
{
width:130%; height: 20px;
}
.div-bottom-scroll
{
width:130%; /*height: 100%;*//*overflow: auto;*/
}
@page
{
page-break-after: always;
}
</style>
<script type="text/javascript">
    // Change the selector if needed
// var $table = $('table'),
//     $bodyCells = $table.find('tbody tr:first').children(),
//     colWidth;

// // Get the tbody columns width array
// colWidth = $bodyCells.map(function() {
//     return $(this).width();
// }).get();

// // Set the width of thead columns
// $table.find('thead tr').children().each(function(i, v) {
//     $(v).width(colWidth[i]);
// }); 
</script>
