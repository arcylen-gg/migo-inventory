@extends('member.layout')
@section('content')

<!-- <form method="post"> -->
  <div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
      <div>
        <i class="fa fa-angle-double-up" aria-hidden="true"></i>
        <h1>
          <span class="page-title">Distribution <i class="fa fa-angle-double-right"></i> Cashback</span>
          <small>
            Distribute of cashback points
          </small>
        </h1>
        <button type="button" id="button1" class="btn btn-sm dist_cashback"><span><i class="fa fa-refresh" aria-hidden="true"></i></span>  Distribute</button>
      </div>
    </div>
  </div>
<!-- </form> -->


<div class="panel panel-default panel-block panel-title-block" id="top">
  <div class="panel-heading">
  <br>
  <br>         
  <table class="table table-bordered table-condensed">
    <thead>
      <tr>
        <th><center>Customer Name</center></th>
        <th><center>Slot No.</center></th>
        <th><center>Cashback Points</center></th>
      </tr>
    </thead>
    <tbody>
      @foreach($_slot as $slot)
        <tr>
          <td><center>{{$slot->first_name}} {{$slot->middle_name}} {{$slot->last_name}}</center></td>
          <td><center><a href="" class='underline'><span>{{$slot->slot_no}}</span></a></center></td>
          <td><center>{{$slot->rank_cashback}}</center></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
</div>
</div>

<form type="POST" class="secured_distribute" action="/member/mlm/distribute_cashback/distribute">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
</form>
@endsection
<style>
  div button {
    float: right;
    margin-right:10px;

  }

  #classModal {}

  .modal-body {
    overflow-x: auto;
  }


  a.underline {
    text-decoration: none;
  }
  a.underline span {
    display: inline-block;
    border-bottom: 1px solid blue;
    font-size: 15px;
    line-height: 12px;
  }

  th {
    white-space: nowrap;
  }

  td{
    white-space: nowrap;

  }
  
  .page-title{
    color: skyblue !important;   
  }
  #button1{
    border-color:#36a6fd;
    background: white !important; 
    color: skyblue !important; 
  }
  #button2{
    border-color:#36a6fd;
    background: white !important; 
    color: skyblue !important; 
  }

  #button3{
    border-color:#36a6fd;
    background: white !important; 
    color: skyblue !important; 
  }

</style>
<style> 

  .stylish-input-group .input-group-addon{
    background: white !important; 

  }
  .stylish-input-group .form-control{
    border-color:#ccc;
  }
  .stylish-input-group button{
    border:0;
    background:transparent;
  }

  .datepicker{
    border-color:#ccc;
  }

</style>

@section('script')
<script type="text/javascript">
  $(".dist_cashback").click(function()
  {
      localStorage.setItem("show_dist_message",1);
      $(".secured_distribute").submit();
  });

  if(localStorage.getItem("show_dist_message") == 1)
  {
    alert("Successfully distributed");
    localStorage.clear("show_dist_message");
  }
</script>
@endsection

