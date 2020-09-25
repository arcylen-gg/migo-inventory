@extends('member.layout')
@section('css')

@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Chart of Accounts</span>
                <small>
                    Categorize money your business earns or spends. Or, track the value of your assets and liabilities.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-primary pull-right popup" link="/member/accounting/chart_of_account/popup/add" >Add Accounts</a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-star"></i> Active Accounts</a></li>
        <!-- <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-trash"></i> Inactive Accounts</a></li> -->
    </ul>
    <div class="search-filter-box">
            <div class="col-md-4 col-md-offset-8" style="padding: 10px">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control global-search" url="/member/accounting/chart_of_account" data-value="1" placeholder="Search" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
    <div class="tab-content">
        <div class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive load-data" target="coa_data">
                <div id="coa_data">
                    <table class="table table-hover table-condensed collaptable">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Balance Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                @include('member.accounting.load_chart_account')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="/assets/mlm/jquery.aCollapTable.min.js"></script>
<script type="text/javascript">
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
    function submit_done(data)
    {
        if(data.response_status == 'success')
        {
            if(data.redirect_to)
            {
                window.location.href = data.redirect_to;
            }
        }
    }

    var coa_list = new coa_list();

    function coa_list()
    {
        init();

        function init()
        {
            document_ready();
        }

        function document_ready() 
        {     
            action_collaptible();
        }

        function action_collaptible()
        {
          $('.collaptable').aCollapTable(
          { 
              startCollapsed: false,
              addColumn: false, 
              plusButton: '&#9658; ', 
              minusButton: '&#9660; '
          });
        }
    }
</script>
@endsection