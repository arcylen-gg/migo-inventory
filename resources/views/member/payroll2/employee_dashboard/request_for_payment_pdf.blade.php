<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <base href="{{ URL::to('/') }}">
    <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


</head>
<body>
 <div class="title" style="width:700px;padding:0px;border:2px solid black;padding-top:30px;">
        <h6><strong>Philharbor Ferries And Port Services, Inc.</strong></h6><h3 style="margin-left:450px;float:right !important;margin-top: -55px;"><strong>REQUEST FOR PAYMENT</strong></h3>
    </div>

    <div class="payeeinfo"  style="width:720px;padding:0px;">
            <div class="payee" style="width:350px;border-right: 2px solid black;border-left: 2px solid black;background-color:#D8D8D8;">
            <h6 style="margin:0px;">&nbsp;PAYEE</h6>
            </div>
            <div class="ppa" style="width:350px;border-right: 2px solid black;border-left: 2px solid black;background-color:white;">
            <h6 style="margin:0px;text-align: center;">&nbsp;{{$employee["payroll_employee_display_name" ]}}</h6>
            </div>
            <div class="date" style="width:348px;border-right: 2px solid black;background-color:#D8D8D8;margin-right:15px;float:right;margin-top:-32px;">
            <h6 style="margin:0px;">&nbsp;DATE</h6>
            </div>
             <div class="date" style="width:348px;border-right: 2px solid black;background-color:white;margin-right:15px;float:right;margin-top:0px;">
            <h6 style="margin:0px;text-align: center;">&nbsp;{{$request_payment_info["payroll_request_payment_date" ]}}</h6>
            </div>


    </div>

    <div class="purpose" style="width:700px;padding: 0px;border:2px solid black;border-top-color: transparent;margin-top:0px;">
            <div class="purpose" style="width:350px;border-right: 2px solid black;background-color:#D8D8D8;">
            <h6 style="margin:0px;">PURPOSE</h6>
            </div>

            <div class="payment" style="padding-top:20px;width:350px;border-right: 2px solid black;background-color:white;">
            <h6 style="margin:0px;text-align: center;">{{$request_payment_info["payroll_request_payment_name" ]}}</h6>
            </div>

            <div class="amount" style="width:348px;background-color:#D8D8D8;margin-right:-2px;float:right;margin-top:-52px;border-bottom-color: 2px transparent solid">
            <h6 style="margin:0px;">AMOUNT IN FIGURES</h6>
            </div>

         <div class="amount" style="width:348px;background-color:white;margin-right:-348px;float:right;margin-top:36px;">
            <h6 style="margin:0px;text-align: center">{{$request_payment_info["payroll_request_payment_total_amount" ]}}</h6>
            </div>
    </div>

      <div class="words" style="width:702px;padding: 0px;border-left: 2px solid black">

            <div class="total_amount_words" style="width:704px;border-right: 2px solid black;background-color:#D8D8D8;">
            <h6 style="margin:0px;">TOTAL AMOUNT IN WORDS</h6>
            </div>

            <div class="words_total" style="padding-top:10px;width:704px;border-right: 2px solid black;background-color:white;">
            <h6 style="margin:0px;text-align: center;"><strong>{{$total_in_words}}</strong></h6>
            </div>

      </div>

    <div class="particulars" style="width:700px;padding: 0px;border:2px solid black;border-top-color: transparent;">

            <div class="particularss" style="width:350px;border-right: 2px solid black;background-color:#D8D8D8;">
            <h6 style="margin:0px;text-align:center;"><strong>PARTICULARS</strong></h6>
            </div>

            <div class="payment" style="padding-top:20px;width:350px;border-right: 2px solid black;background-color:white;">
                @foreach($_request_payment_sub_info as $particulars)
                <h6 style="margin:0px;text-align: center;">{{$particulars->payroll_request_payment_sub_description}}</h6>
                @endforeach
                <h6 style="margin:0px;text-align: center;padding-top: 50px;">*****nothing follows*****</h6>
            </div>

             <div class="remarks" style="width:175px;border-right: 2px solid black;border-top: 2px solid black;background-color:#D8D8D8;">
            <h6 style="margin:0px;text-align:center;">REMARKS</h6>
            </div>

            <div class="remarksvalue" style="padding:20px;width:135px;background-color:white;border-top:2px solid black;border-right: 2px solid black;">
            <h6 style="margin:0px;text-align:center;font-size: 8px;"><strong>{{$request_payment_info["payroll_request_payment_remark" ]}}</strong></h6>
            </div>

            <div class="remarks" style="width:175px;border-top: 2px solid black;background-color:#D8D8D8;float:right;margin-right:347px;margin-top:-70px;border-right:2px solid black">
            <h6 style="margin:0px;text-align:center;">ATTACH FILE</h6>
            </div>

            <div class="remarksvalue" style="padding:20px;width:135px;background-color:white;float:right;margin-right:347px;margin-top:0px;border-top:2px solid black;border-right: 2px solid black">
            <h6 style="margin:0px;text-align:center;font-size: 8px;"><strong>SAMPLE FILE</strong></h6>
            </div>



            @if(count($_request_payment_sub_info) > 1)
            <div class="amount" style="width:350px;background-color:#D8D8D8;margin-right:-2px;float:right;margin-top:-220px;">
            <h6 style="margin:0px;text-align: center;"><strong>AMOUNT</strong></h6>
            </div>
            @else
            <div class="amount" style="width:350px;background-color:#D8D8D8;margin-right:-2px;float:right;margin-top:-190px;">
            <h6 style="margin:0px;text-align: center;"><strong>AMOUNT</strong></h6>
            </div>
            @endif
            <div class="payment" style="padding-top:100px;width:348px;background-color:white;float:right;margin-top: -65px;margin-right:-348px;padding-bottom: 80px;">

              @foreach($_request_payment_sub_info as $amount)
                <h6 style="margin:0px;text-align: center;">{{$amount->payroll_request_payment_sub_amount}}</h6>
                @endforeach
            <h6 style="margin:0px;text-align: center;padding-top: 50px;"><span style="color:white">*****nothing follows*****</span></h6>
            </div>
    </div>



      <div class="words" style="width:704px;padding: 0px;">


            <div class="payee" style="width:350px;background-color:#D8D8D8;border-left:2px solid black;border-bottom: 2px solid black">
            <h6 style="margin:0px;">TOTAL</h6>
            </div>
            <div class="date" style="width:350px;background-color:#D8D8D8;margin-right:-2px;float:right;margin-top:-18px;border-right:2px solid black;border-bottom:2px solid black;border-left:2px solid black">
            <h6 style="margin:0px;text-align: center;"><strong>{{$request_payment_info["payroll_request_payment_total_amount" ]}}</strong></h6>
            </div>


      </div>

@php
$count = 1;
$count1 = "Checked by:";
$count2 = "Verified by:";
$count3 = "Noted by:";
$count4 = "Approved by:";
@endphp
        @foreach($approver_info as $approver)
            <div style="float-left;display: inline;">
            <h5 style="font-size: 10px;margin-bottom: 10px;">@if($count == 1){{$count1}}@elseif($count == 2){{$count2}}@elseif($count == 3){{$count3}}@elseif($count == 4){{$count4}}@endif</h5>
            <h5 style="font-size: 10px;float-left;display: inline;">_______________________________________</h5>
            <h5 style="font-size:10px;float-left;display: inline;">{{$approver['payroll_employee_display_name']}}<br>{{$approver['payroll_jobtitle_name']}}</h5>
            </div>
            <br>
            @php
            $count++
            @endphp
        @endforeach


</body>
</html>