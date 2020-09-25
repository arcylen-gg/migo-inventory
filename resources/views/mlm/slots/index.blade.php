@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Slots';
$data['sub'] = 'The nickname of the slot will be used as reference for the registration page';
$data['icon'] = 'icon-sitemap';
?>
@include('mlm.header.index', $data)

<div class="col-md-6">
  <div class="box clearfix" style="overflow: hidden !important;">
    <div class="box-header with-border">
      <h3 class="box-title">Choose Default Slot</h3>
    </div>
    <!-- /.box-header -->
    <form class="global-submit" method="post" action="/mlm/slots/set_nickname">
    {!! csrf_field() !!}
    <div class="box-body">
      <label>Slot</label>
      <input type="text" class="form-control" name="active_slot" value="{{ isset($active->slot_no) ? $active->slot_no : ''}}">

      <label>Slot Nickname</label>
      <input type="text" class="form-control" name="slot_nickname" value="{{ isset($active->slot_nick_name) ? $active->slot_nick_name : ''}}" readonly>

      <hr>
      <button class="btn btn-primary pull-right">Set Default</button>
    </div>
    </form>
  </div>
  <div class="box clearfix" style="overflow: hidden !important;">
    <div class="box-header with-border">
      <h3 class="box-title">Code Vault</h3>
      <button class="btn btn-primary pull-right popup" link="/mlm/slot/use_product_code" size='md'>Use Product Code</button>
    </div>
    <!-- /.box-header -->
    <form class="global-submit" method="post" action="/mlm/slot/check_add">
    {!! csrf_field() !!}
    <div class="box-body">
      <label>Create Slot</label>
      <select name="membership_code_id" class="form-control membership_code_id">
        @if(count($_code) != 0)
          @foreach($_code as $code)
            <option value="{{$code->membership_code_id}}">{{$code->membership_activation_code}} {{$code->membership_type}} ({{$code->membership_name}})</option>
          @endforeach
        @else
            <option>NO MEMBERSHIP CODE</option>
        @endif
      </select>
      <hr>
      @if(count($_code) != 0)
        <button class="btn btn-primary pull-right use_code_mem" style="margin-left:20px;">Use Code</button>
        <button class="btn btn-primary pull-right trans_mem_code" type="button">Transfer code</button>
      @endif
    </div>
    </form>
  </div>

  {{--
  <div class="box clearfix" style="overflow: hidden !important;">
    <div class="box-header with-border">
      <h3 class="box-title">Slot Transfer</h3>
    </div>
    <!-- /.box-header -->
    <form class="global-submit" method="POST" action="/mlm/slots/before_transfer_slot">
    {!! csrf_field() !!}
    <div class="box-body">
        <label>Slot</label>
        <select name="slot_id" class="form-control">
            @foreach($all_slots_show as $slot_show)
                <option value="{{$slot_show->slot_id}}">{{$slot_show->slot_no}} ({{$slot_show->membership_name}})</option>
            @endforeach
        </select>
      <hr>
      <button class="btn btn-primary pull-right">Transfer</button>
    </div>
    </form>
  </div>
  --}}




  @if(count($_item_code) >= 1)
    <div class="box clearfix" style="overflow: hidden !important;">
      <div class="box-header with-border">
        <h3 class="box-title">Product Codes</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
          <label>Code</label>
          <select name="item_code_id" class="form-control item_code_id">
              @foreach($_item_code as $item_code)
                  <option value="{{$item_code->item_code_id}}">{{$item_code->item_activation_code}}</option>
              @endforeach
          </select>
        <hr>
        <button class="btn btn-primary pull-right use_prod_code" style="margin-left:20px;">Use code</button>
        <button class="btn btn-primary pull-right trans_prod_code">Transfer code</button>
      </div>
    </div>
  @endif
</div>  
<div class="col-md-6">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">SLOT LIST </h3>
      <button class="btn btn-primary pull-right popup" link="/mlm/slots/manual_add_slot">Add new slot</button>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-bordered">
        <thead>
            <th>Slot</th>
            <th>Date Created</th>
            <th>Current Wallet</th>
            <th>Membership</th>
            <th></th>
            @if($enabled_upgrade_slot == 1)
            <th></th>
            @endif
        </thead>
        <tbody>
            @if(count($all_slots_p) >= 1)
              @foreach($all_slots_p as $key => $value)
                <tr>
                  <td>{{$value->slot_no}}</td>
                  <td>{{$value->slot_created_date}}</td>
                  <td>{{$value->slot_wallet_current}}</td>
                  <td class="membership_{{$value->slot_id}}">{{$value->membership_name}}</td>
                  <td><form class="global_submit" action="/mlm/changeslot" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="slot_id" value="{{$value->slot_id}}">
                        <button class="btn btn-primary">Use Slot</button>
                      </form>
                  </td>
                  @if($enabled_upgrade_slot == 1)
                  <td><button class="btn btn-primary popup" link="/mlm/slots/upgrade_slot/{{$value->slot_id}}" type="button" size="lg" data-toggle="modal" data-target="#global_modal">Upgrade Slot</button></td>
                  @endif
                </tr>
              @endforeach
            @else
            <tr>
                <td colspan="40"><center>---No Record Found---</center></td>
            </tr>
            @endif
            <tr>
              <td colspan="40"><center>{!! $all_slots_p->render() !!}</center></td>
            </tr>
        </tbody>

      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
      
    </div>
  </div>
  <!-- /.box -->
</div>  
<div class="hidden">
  <button class="check_slot_button popup" link=""> 
</div>
@endsection
@section('js')
<script type="text/javascript">
on_click();

function submit_done(data)
{
  if(data.status == 'success')
  {
    toastr.success(data.message);
  }  
  else if(data.status == "sucess-slot")
  { 
    var link = "/mlm/slot/manual_add?membership_id="+data.encrypted;
    action_load_main_modal(link,"");
    // var link = "/mlm/membership_active_code/"+data.encrypted;
    // window.location.href = link;
  }
  else if(data.status == 'success-upgrade')
  {
    toastr.success(data.message);
    $('#global_modal').modal('toggle');
    $('.membership_'+data.slot_id).text(data.membership_name);
  }
  else if(data.status == 'success-manual')
  {
    var link = "/mlm/slot/manual_add?membership_id="+data.encrypted;
    action_load_main_modal(link,"");
  }
  else if(data.response_status == "warning_1")
  {
    var erross = data.warning_validator;
    $.each(erross, function(index, value) 
    {
        toastr.warning(value);
    }); 
  }
  else if(data.response_status == 'warning_2')
  {
    toastr.warning(data.error);
  }
  else if(data.response_status == 'success_add_slot')
  {
    toastr.success('Congratulations, Your slot is created.');
    window.location = "/mlm";
  }
  else if(data.status == 'success_before_transfer_slot')
  {
    var link = "/mlm/slots/transfer_slot?slot_id="+data.encrypted;
    action_load_main_modal(link,"");
  }
  else if(data.status == 'success_transfer_slot')
  {
    toastr.success('Transfer done.');
    window.location = "/mlm";
  }
  else if(data.status == 'success-check-prod-code')
  {
    var link = "/mlm/slots/item_code?item_code_id="+data.item_code_id;
    action_load_main_modal(link,"");
  }
  else if(data.status == 'success-prod-code')
  {
    toastr.success('Done.');
    window.location = "/mlm/slots";
  }
  else if(data.status == 'success-transfer-prod-code')
  {
    toastr.success('Done.');
    window.location = "/mlm/slots";
  }
  else if(data.status == 'success-transfer-mem-code')
  {
    toastr.success('Done.');
    window.location = "/mlm/slots";
  }
  else
  {
    toastr.warning(data.message);
  }
}

function on_click()
{
  $(".use_prod_code").click(function()
  {
    var link = "/mlm/slots/item_code?item_code_id="+$(".item_code_id").val();
    action_load_main_modal(link,"");
  });
  $(".trans_prod_code").click(function()
  {
    var link = "/mlm/slots/transfer_item_code?item_code_id="+$(".item_code_id").val();
    action_load_main_modal(link,"");
  });
  $(".trans_mem_code").click(function()
  {
    // alert($(".membership_code_id").val());
    var link = "/mlm/slots/transfer_mem_code?mem_code_id="+$(".membership_code_id").val();
    action_load_main_modal(link,"");
  });
}

</script>
@endsection