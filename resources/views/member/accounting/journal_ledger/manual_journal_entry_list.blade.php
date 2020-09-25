@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Journal Entry &raquo; List </span>
                <small>
                    List of Journal Entry
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/accounting/journal" >Create Journal Entry</a>
            <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/accounting/journal/all-list" >All Journal Entry</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="form-group tab-content panel-body sir_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Journal No</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_journal)
                        @foreach($_journal as $journal)
                            <tr>
                                <td>{{$journal->je_id}}</td>
                                <td>{{dateFormat($journal->je_entry_date)}}</td>
                                <td>{{currency("PHP",$journal->total)}}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a class="btn btn-primary btn-grp-primary" href="/member/accounting/journal?id={{$journal->je_id}}">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
</div>
@endsection