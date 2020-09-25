@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>
<link rel="stylesheet" type="text/css" href="/email_assets/email_css/create_email.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-envelope"></i>
            <h1>
                <span class="page-title">Press Release Email System</span>
            </h1>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <header class="header_email">
    <div class="panel-body form-horizontal">
        <div class="form-group">
           <h1 class="create_news"> Sent Email List</h1>
        </div>     
    </div>
    </header>

    <table class="table table-hover table_archive_email">
  <thead>
    <tr>
      <th>To</th>
      <th>From</th>
      <th>Subject</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    @foreach($_sent_email as $sent_email)
    <tr>
      <td>{{$sent_email->to}}</td>
      <td>{{$sent_email->from}}</td>
      <td>{{$sent_email->email_title}}</td>
      <td>{{$sent_email->email_time}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
@endsection   

