@extends('member.layout')
@section('content')
<div class="row">
    <div class="col-md-12 text-center">
        <h3>Welcome to Digima House</h3>
    </div>
</div>

<!-- TRIAL COUNT DOWN -->
<h2>Journal Entry</h2>        
<table class="table table-striped">
<thead>
  <tr>
    <th>Date</th>
    <th>Type</th>
    <th>Particular</th>
    <th>Name</th>
    <th>Item</th>
    <th>Description</th>
    <th>Account</th>
    <th>Debit</th>
    <th>Credit</th>
  </tr>
</thead>

<tbody>
  @foreach($tbl_journal_entry as $journal)
    @foreach($journal['journal_line'] as $key => $journal_line)
      <tr>
        <td>{{$key == 0 ? $journal['je_entry_date'] : ''}}</td>
        <td>{{$key == 0 ? $journal['je_reference_module'] : ''}}</td>
        <td>{{$key == 0 ? $journal['je_remarks'] : ''}}</td>
        <td></td>
        <td>{{$journal_line['item_name']}}</td>
        <td>{{$journal_line['jline_description']}}</td>
        <td>{{$journal_line['account_name']}}</td>
        <td>{{$journal_line['jline_type'] == 'debit' ? currency('',$journal_line['jline_amount']) : ''}}</td>
        <td>{{$journal_line['jline_type'] == 'credit' ? currency('',$journal_line['jline_amount']) : ''}}</td>
      </tr>
    @endforeach
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{currency('',$journal['debit'])}}</td>
        <td>{{currency('',$journal['credit'])}}</td>
      </tr>
  @endforeach
</tbody>
</table>

@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/dashboard.css">
@endsection
