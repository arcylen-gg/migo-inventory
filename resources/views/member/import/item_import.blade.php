@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-user-secret"></i>
            <h1>
                <span class="page-title">Items &raquo; Import</span>
                <small>
                </small>
            </h1>
            <a href="/member/item/import/item-template" class="btn btn-custom-white pull-right">Download Item Template</a>
            <a href="/member/item/import/item-export-error" class="btn btn-custom-white pull-right import-error hidden"></a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block clearfix">
    <div class="col-md-6">
        <h4><span class="counter">0</span> Items Added</h4>
        <div class="progress">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
        </div>
        <div id="ImportContainer">
            <form action="/member/item/import/url" id="myDropZoneImport" class="dropzone" method="post" enctype="multipart/form-data">
                <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="files" name="files[]" style="display: none"><br>
                <div class="dz-message">
                    <span class="needsclick">
                        <h1><b>DRAG & DROP</b></h1>
                        <h4>your CSV File here or click it to browse</h4>
                    </span>
                </div>
                <div class="pull-right">
                    <output id="list"></output> 
                </div>
            </form>
        </div>
        </br>
        </br>
    </div>
    <div class="col-md-6">
        </br>
        <form role="form" method="post" class="import-validation">
            <label>Automatic creation if not exist:</label>
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" name="category" >Category</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="income_account">Income Account</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="expense_account">Expense Account</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="asset_account">Asset Account</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="manufacturer">Manufacturer</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="form-control btn btn-custom-primary btn-submit" disabled="disabled">Generate Import</button>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <div class="table-responsive" style="overflow: auto">
            <table class="table table-condensed table-stripped table-hover table-import-container table-bordered">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>UM</th>
                        <th>Category</th>
                        <th>Sales Information</th>
                        <th>Sales Price</th>
                        <th>Income Account</th>
                        <th>Sale to Customer</th>
                        <th>Purchase From Supplier</th>
                        <th>Purchasing Information</th>
                        <th>Purchase Cost</th>
                        <th>Expense Account</th>
                        <th>Barcode</th>
                        <th>Qty on Hand</th>
                        <th>Reorder Point</th>
                        <th>As of Date</th>
                        <th>Asset Account</th>
                        <th>Packing Size</th>
                        <th>Manufacturer</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
    .custom-upload
    {
        width: 100%;
        height: 100px;
        border: black dashed 2px;
        background-color: white;
    }
    .dropzone
    {
        position: relative;
        border: dashed 3px #76b6ec;
        min-height: 100px;
        padding: 5px 15px;
        cursor: pointer;
    }
    .dropzone .dz-message *
    {
        text-align: center;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        left: 0;
        right: 0;
        margin: auto;
    }
    .dropzone .dz-preview
    {
        position: initial;
    }
    
</style>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/plugin/jquery-csv-master/src/jquery.csv.js"></script>
<script type="text/javascript">
    var url_link = '/member/item/import/item-read-file';
</script>
<script type="text/javascript" src="/assets/member/js/import_csv.js"></script>
@endsection