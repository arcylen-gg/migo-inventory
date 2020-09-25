
    <input type="hidden" class="print_this" value="{{Request::input('print')}}">
    <!-- NO PRODUCT YET -->
    <div class="clearfix printable centerr">
        <div>
            <div class="clearfix">
                <table class="table">
                    <tr>
                        <td>
                            <div class="col-md-12" style="margin-left:21px;">
                                <span style="font-size: 40px; color:#f1c40f; font-weight: bold;">RECEIPT</span>
                            </div>
                            <div class="col-md-12" style="margin-left:24px;">
                                Date:
                                <span class="underlined_text">
                                    {{Carbon\Carbon::parse($invoice["item_date_created"])->format('F d, Y | g:i:s A')}}
                                </span>
                            </div>
                            <div class="col-md-12" style="margin-left:24px;">
                                Item #:
                                <span class="underlined_text_2">
                                    <center>{{$invoice["item_id"]}}</center>
                                </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12" style="border-top: 5px solid #1E5649;">
                <div class="tab-content codes_container">
                    <div id="all" class="tab-pane fade in active">
                        <div class="form-group order-tags"></div>
                        <div class="table-responsive">
                            <table class="table table-condensed">                                          
                                    <tr>                                
                                        <td>Item Name:{{$invoice["item_name"]}}</td>
                                    </tr>
                                    <tr>                                
                                        <td>SKU:{{$invoice["item_sku"]}}</td>
                                    </tr>    
                                    <tr>                                
                                        <td>Category:{{$invoice["category_name"]}}</td>
                                    </tr>     
                                    <tr>                                
                                        <td>Price:{{$invoice["item_price"]}}</td>
                                    </tr>                     
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       

<style type="text/css">
    .centerr {
        margin: auto;
        width: 50%;
        padding: 10px;
    }
    .tadble thead tr th
    {
        background-color: #1E5649;
        color: #fff;
    }
    .tadble
    {
        border-collapse: collapse;
    }
    .tadble th, .tadble td
    {
        border: 1px dotted #1E5649 !important;
    }
    .underlined_text
    {
        display: inline-block; 
        line-height: 0.9; 
        border-bottom: 1px dotted; 
        min-width: 159px;
    }

    .underlined_text_2
    {
        display: inline-block; 
        line-height: 0.9; 
        border-bottom: 1px dotted; 
        min-width: 160px;
    }


    .bill-title
    {
        background-color: #BFBFBF;
        text-align: center;
        min-width: 100px;
        font-size: 25px;
        font-weight: bold;
    }

    @media print 
    {
        div, nav
        {
            display: hide;
        }
        .modal
        {
            display: block;
        }
        .printable
        {
            display: block;
        }
    }
</style>
@if(Request::input("print") == 'now')
<script>
    (function () {
    var js;
    if (typeof JSON !== 'undefined' && 'querySelector' in document && 'addEventListener' in window) {
    js = '/assets/external/jquery.minv2.js';
    } else {
    js = '/assets/external/jquery.minv1.js';
    }
    document.write('<script src="' + js + '"><\/script>');
    }());
    </script>
@endif
<script type="text/javascript">

</script>