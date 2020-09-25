@extends('member.layout')
@section('css')
<style type="text/css">
  *
  {
    font-family: 'Titillium Web',sans-serif;
    font-size: 14px;
    line-height: 1.428571429;
  }
</style>
@endsection
@section('content')
<form class="global-submit" action="/member/utilities/add-access" method="POST" >
<input type="hidden" name="_token" value="{{csrf_token()}}" >
<input type="hidden" name="position_id" value="{{Input::get('id','no position id selected')}}" >
<div class="panel panel-default panel-block panel-title-block">
  <div class="panel-heading">
    <div>
      <i class="fa fa-user-secret"></i>
      <h1>
        <span class="page-title">Utilities &raquo; access</span>
        <small>
        </small>
      </h1>
      <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Save Access</button>
    </div>
  </div>
</div>

<div id="menu" class="col-md-6">
  <div class="table-responsive load-data" target="coa_data">
      <div id="coa_data">
          <table class="table table-hover table-condensed collaptable">
              <thead style="text-transform: uppercase">
                  <tr>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                @include('member.utilities.admin_access_setting')
              </tbody>
          </table>
      </div>
  </div>
</form>
@endsection
@section('script')
<script type="text/javascript" src="/assets/mlm/jquery.aCollapTable.min.js"></script>
<script type="text/javascript">
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif

    $('.collaptable').aCollapTable(
    { 
        startCollapsed: true,
        addColumn: false, 
        plusButton: '&nbsp;&nbsp; &#9658; ', 
        minusButton: '&nbsp;&nbsp; &#9660; '
    });

    function submit_done(data)
    {
      if(data.response_status == 'success')
      {
        toastr.success(data.message);
      }
    }
</script>
@endsection