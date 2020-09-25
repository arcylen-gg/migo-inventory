@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Wallet Encashment';
$data['sub'] = 'In this tab you can request/view encashment history';
$data['icon'] = 'fa fa-money';
?>
@include('mlm.header.index', $data)

<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Encashment History</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
      <table class="table table-bordered">
        <thead>
            <th>From</th>
            <th>To</th>
            <th>Tax</th>
            <th>Processing Fee</th>
            <th>Amount</th>
            <th>Total</th>
            <th>Status</th>
            <th>Breakdown</th>
            <th>PDF</th>
        </thead>
        <tbody>
        @if(count($history) >= 1)
          @foreach($history as $key => $value)
          <?php 

          $currency = $value->encashment_process_currency;
          $convertion = $value->encashment_process_currency_convertion;
          $tax_converted = $value->enchasment_process_tax * $convertion;
          $process_fee = $value->enchasment_process_p_fee * $convertion;
          $taxed = $value->encashment_process_taxed * $convertion;
          $total = $value->wallet_log_amount * $convertion;
          $denied = $value->wallet_log_denied_amount * $convertion;

          ?>
            <tr>
              <td>{{$value->enchasment_process_from}}</td>
              <td>{{$value->enchasment_process_to}}</td>

              @if($value->enchasment_process_tax_type == 1)
              <td>{{$value->enchasment_process_tax}}%</td>
              @else
              <td>{{currency($currency, $tax_converted)}}</td>
              @endif
              @if($value->enchasment_process_p_fee_type == 1)
              <td>{{$value->enchasment_process_p_fee}}%</td>
              @else
              <td>{{currency($currency, $process_fee)}}</td>
              @endif

              <td>{{currency($currency, $taxed)}}</td>
              <td>{{currency($currency, $total * -1)}}</td>

              @if($value->encashment_process_type == 0)
              <td class="alert alert-warning">Pending</td>
              @elseif($value->encashment_process_type  == 1)
              <td class="alert alert-success">Processed</td>
              @else
              <td class="alert alert-danger">Denied ({{currency($currency, $denied)}})</td>
              @endif

              <td><button class="btn btn-primary" onclick="show_breakdown({{$value->encashment_process}}, {{$value->slot_id}})">Breakdown</button></td>
              <td><a target="_blank" href="/mlm/encashment/view/breakdown/{{$value->encashment_process}}/{{$value->slot_id}}?pdf=true" class="btn btn-success">PDF</a></td>
            </tr>
          @endforeach
        @else
        <tr>
          <td colspan="40">
            <center>No Encashment History.</center>
          </td>
        </tr>
        @endif
        </tbody>
      </table>
      {!! $history->render() !!}
    </div>
    <div class="box-footer clearfix">
      
    </div>
  </div>
</div>     
<div class="col-md-6">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Breakdown</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body breakdown_slot_c">
    </div>
  </div>
</div>  
<div class="col-md-6">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Encashment Details</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
    {!! $encashment !!}
    </div>
  </div>
</div>  
@if($encashment_settings->enchasment_settings_auto == 1)
<form class="global-submit" id="form_ecnash" method="post" action="/mlm/encashment/request">
{!! csrf_field() !!}
<div class="col-md-8">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Request Encashment</h3>
      <span class="pull-right">
        
      </span>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-bordered">
        <thead>
          <th><input type="checkbox" class="select_all_logs"> Select All</th>
          <th>Date Earned</th>
          <th>Amount</th>
          <th>Complan</th>
        </thead>
        <tbody>
        @if(count($unprocessed) >= 1)
          @foreach($unprocessed as $key => $value)
            <tr>
              <td>
                @if($value['wallet_log_amount'] <= 0 )
                <input type="checkbox" class="check_box_auto" value="{{$value['wallet_log_amount']}}" name="wallet_log_id[{{$value['wallet_log_id']}}]" onclick="return false;" checked>
                @else
                <input type="checkbox" class="check_box_single" value="{{$value['wallet_log_amount']}}" name="wallet_log_id[{{$value['wallet_log_id']}}]">
                @endif
              </td>
              <td>{{$value['wallet_log_date_created']}}</td>
              <td>{{currency('PHP', $value['wallet_log_amount'])}}</td>
              <td>{{$value['wallet_log_plan']}}</td>
            </tr>
          @endforeach
        @else
        <tr>
          <td colspan="40"><center>No Earnings To Request</center></td>
        </tr>
        @endif
        </tbody>
      </table>
    </div>
    <div class="box-footer clearfix">
      
    </div>
  </div>
</div>
<div class="col-md-4">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Breakdown</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body clearfix">
      <label>Amount</label>
      <input type="number" class="form-control b_amount" value="0" readonly>
      <label>Processing Fee</label>
      <input type="number" class="form-control b_fee" readonly>
      <label>Tax</label>
      <input type="number" class="form-control b_tax" readonly>
      <label>Total</label>
      <input type="number" class="form-control b_total" readonly>

      @if(count($currency_set) >= 2)
      <label>Select Currency</label>
      <select class="form-control currency_set_select" name="currency_set" class="">
        @foreach($currency_set as $key => $value)
          <option value="{{$value->en_cu_id}}" convertion="{{$value->en_cu_convertion}}" iso="{{$value->iso}}">{{$value->iso}} - {{$value->en_cu_name}}</option>
        @endforeach
      </select>
      <label class="label_converted">Converted</label>
      <input type="text" class="form-control convertion" step="0.1" readonly="readonly">
      @endif
    </div>
    <div class="box-footer clearfix">
      <button class="btn btn-primary pull-right bind">Request For Encashment</button>
    </div>
  </div>
</div>  
</form>  
@else

@endif
    
@endsection

@section('js')
<script type="text/javascript">

function inititalize()
{
 $('.check_box_auto').each(function(){
    if($(this).is(':checked'))
    {
      var amount = $(this).val();
      add_amount(amount);
    }
  });
}



$('.bind').on('click', function(){
  // $('#form_ecnash').submit();
  $(this).addClass('hide');
});
@if(isset($encashment_settings->enchasment_settings_tax))
  var tax = {{$encashment_settings->enchasment_settings_tax}};
  var tax_p = {{$encashment_settings->enchasment_settings_tax_type}};

  var fee = {{$encashment_settings->enchasment_settings_p_fee}};
  var fee_p = {{$encashment_settings->enchasment_settings_p_fee_type}};

@else
var tax = 0;
var tax_p = 0;
var fee = 0;
var fee_p = 0;
@endif
inititalize();
  // function 
  var total_amount = 0;
  $('.currency_set_select').on('change', function (){
    var convertion = $('option:selected', this).attr('convertion');
    var iso = $('option:selected', this).attr('iso');
    var total_amount_a = $('.b_total').val();
    var converted = total_amount_a * convertion;
    console.log(total_amount_a);
    var amount_currency_iso = formatPHP(iso, converted);
    $('.convertion').val(amount_currency_iso);
  });
  function change_currency()
  {
    var convertion = $('.currency_set_select option:selected').attr('convertion');
    var iso = $('.currency_set_select option:selected').attr('iso');
    var total_amount_a = $('.b_total').val();
    var converted = total_amount_a * convertion;
    var amount_currency_iso = formatPHP(iso, converted);
    $('.convertion').val(amount_currency_iso);
  }
  function add_amount(amount_to_add)
  {
    var amount = $('.b_amount').val();
    amount = parseFloat(amount)
    amount_to_add = parseFloat(amount_to_add);
    amount += amount_to_add; 
    // total_amount += amount;
    // console.log("total_amount", total_amount);
    $('.b_amount').val(parseFloat(amount).toFixed(4));
    add_processing_fee();
  }
  function add_processing_fee()
  {
    var amount = $('.b_amount').val();
    amount = parseFloat(amount);

    if(fee_p == 0)
    {
      $('.b_fee').val(fee);
    }
    else
    {
      var a = 0;
      a = amount * (fee/100);
      $('.b_fee').val(a);
    }  
    add_tax();
  }
  function add_tax()
  {
    var amount = $('.b_amount').val();

    amount = parseFloat(amount);
    var fee = $('.b_fee').val();

    var a = amount - fee;
    
    if(tax_p == 0)
    {
      $('.b_tax').val(tax);
    }
    else
    {
      var b = 0;
      b = a * (tax/100);
      $('.b_tax').val(b);
    }
    total();
  }
  function total()
  {
    var amount = $('.b_amount').val();
    amount = parseFloat(amount);

    var tax = $('.b_tax').val();
    tax = parseFloat(tax);

    var fee = $('.b_fee').val();
    fee = parseFloat(fee);

    var total = amount - tax - fee;

    $('.b_total').val(total);
    change_currency();
  }
  function show_breakdown(encashment_process, slot_id)
  {
    $('.breakdown_slot_c').html('<center><div class="loader-16-gray"></div></center>');
    $('.breakdown_slot_c').load('/mlm/encashment/view/breakdown/'+encashment_process+'/' +slot_id);
  }

$('.select_all_logs').click(function(){
    // $('.b_amount').val(0);
    if($(this).is(':checked'))
    {
        // $('.check_box_single').checked = true;
        $('.check_box_single').each(function(){
          if($(this).is(':checked'))
          {

            var amount = $(this).val();
            add_amount(-amount);
          }
        });

        $(".check_box_single").prop("checked", true);
        $('.check_box_single').each(function(){
          var amount = $(this).val();
          add_amount(amount);
        });

    }
    else 
    { 
        $(".check_box_single").prop("checked", false);
        $('.check_box_single').each(function(){
          console.log();
          var amount = $(this).val();
          add_amount(-amount);
        });
    }
});

$('.check_box_single').click(function(){
  if($(this).is(':checked'))
  {
    var amount = $(this).val();
    add_amount(amount);
  }
  else
  {
    $(".select_all_logs").prop("checked", false);
    var amount = $(this).val();
    add_amount(-amount);
  }
});
function submit_done(data)
{
  if(data.status == 'warning')
  {
    toastr.warning(data.message);
    $('.bind').removeClass('hide');
  }
  else if(data.status =='Success')
  {
    location.reload();
    toastr.success(data.message);
  }
  else if (data.status == 'success')
  {
    toastr.success(data.message);
  }
}
function formatPHP(iso, num) 
{

      num  = parseFloat(num);
      var sign = "-";
      if(num >=  parseFloat(0))
      {
        sign = '';
      }
      console.log('num:' + num);
      console.log('sign' + sign);
      var num2 = parseFloat(num);
      var p = num2.toFixed(2).split(".");
      return iso + " " + sign + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
          return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
      }, "") + "." + p[1];
  }
</script>
@endsection