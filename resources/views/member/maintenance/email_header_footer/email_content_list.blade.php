@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-envelope"></i>
            <h1>
                <span class="page-title">Email Content</span>
                <small>
                    List of Email Content.
                </small>
            </h1>
            <div class="text-right">
            <a class="btn btn-primary panel-buttons popup" link="/member/maintenance/email_content/add" size="md" data-toggle="modal" data-target="#global_modal">Add Email Content</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Email Content</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived Email Content</a></li>
                </ul>
            </div>
            <div class="col-md-4 pull-right">
            </div>
        </div>

        <div class="form-group tab-content panel-body email-content-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email Content Key</th>
                                <th>Email Subject</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_email_content != null)
                            @foreach($_email_content as $email_content)
                            <tr>
                                <td>{{$email_content->email_content_id}}</td>
                                <td>{{$email_content->email_content_key}}</td>
                                <td>{{$email_content->email_content_subject}}</td>
                                <td class="text-center">
                                    <a link="/member/maintenance/email_content/add?id={{$email_content->email_content_id}}" size="md" class="popup">Edit</a> |
                                    <a link="/member/maintenance/email_content/archived/{{$email_content->email_content_id}}/archived" size="md" class="popup">Archived</a> 
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="archived" class="tab-pane fade in">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                         <thead>
                            <tr>
                                <th>#</th>
                                <th>Email Content Key</th>
                                <th>Email Subject</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_email_content_archived != null)
                            @foreach($_email_content_archived as $email_content_archived)
                            <tr>
                                <td>{{$email_content_archived->email_content_id}}</td>
                                <td>{{$email_content_archived->email_content_key}}</td>
                                <td>{{$email_content->email_content_subject}}</td>
                                <td class="text-center">
                                    <a link="/member/maintenance/email_content/archived/{{$email_content_archived->email_content_id}}/restore" size="md" class="popup">Restore</a> 
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
</div>
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/email_content.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->
@endsection