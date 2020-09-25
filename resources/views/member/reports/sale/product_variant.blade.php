@extends('member.layout')

@section('css')

<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
<link rel="stylesheet" href="/assets/member/css/customleftpopover.css" type="text/css" />
@endsection

@section('content')
<input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-area-chart"></i>
            <h1>
                <span class="page-title"><span class="color-gray">Reports/</span>Sales by product variant SKU</span>
                <small>
                Generate your reports
                </small>
            </h1>
            <button class="btn btn-custom-white pull-right" id="btn-date-range" data-placement="bottom"><span class="date-str">{{$range}}</span>&nbsp;<i class="fa fa-caret-down" aria-hidden="true"></i></button>
            <a href="" class="btn btn-custom-red-white margin-right-10 pull-right btn-pdf" data-url="" data-start="{{$start}}" data-end="{{$end}}"><i class="fa fa-file-pdf-o"></i>&nbsp;Export to PDF</a>
            <div class="hide padding0" id="popover-daterange">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="">Starting Date</label>
                            <input type="text" name="" class="form-control indent-13 calendar-text start-date" value="">
                            <span class="pos-absolute calendar-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            
                        </div>
                        <div class="col-md-6">
                            <label for="">Ending Date</label>
                            <input type="text" name="" class="form-control indent-13 calendar-text end-date" value="">
                            <span class="pos-absolute calendar-icon"><i class="fa fa-calendar " aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 pull-right">
                            <button class="btn btn-custom-white pull-right close-popover">Close</button>
                        </div>
                        <div class="col-md-2 pull-right">
                            <button class="btn btn-custom-blue btn-generate-report pull-right">Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <table class="table table-hover table-condensed table-bordered table-sale-month">
            <thead>
                <th class="text-center">Product title</th>
                <th class="text-center">Variant title</th>
                <th class="text-center">Variant SKU</th>
                <th class="text-center">Net quantity</th>
                <th class="text-center">Gross sale</th>
                <th class="text-center">Discounts</th>
                <th class="text-center">Net Sales</th>
                <th class="text-center">Tax</th>
                <th class="text-center">Total Sales</th>
            </thead>
            <tbody class="tbl-monthly">             
                @foreach($item as $key => $prod)
                    <tr>
                        <td><a href="#">{{$prod['category_name']}}</a></td>

                        <td><a href="#">{{$prod["variant_name"]}}</a></td>

                        <td class="text-center"></td>

                        <td class="text-center">{{$prod['quantity_sold']}}</td>

                        <td class="text-center">
                            <span class="pull-left"></span>{{currency('PHP',$prod['gross_sales'])}}
                        </td>

                        <td class="text-center">
                            <span class="pull-left"></span>{{currency('PHP',$prod['discount'])}}
                        </td>

                        <td class="text-center">
                            <span class="pull-left"></span>{{currency('PHP',$prod['net_sales'])}}
                        </td>

                        <td class="text-center">
                            <span class="pull-left"></span>{{currency('PHP',$prod['tax'])}}
                        </td>

                        <td class="text-center">
                            <span class="pull-left"></span>{{currency('PHP',$prod['total'])}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/report/sale/product_variant.js"></script>
<script type="text/javascript" src="/assets/member/js/calendar.js"></script>
@endsection