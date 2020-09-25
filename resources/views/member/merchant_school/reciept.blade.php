<?php 
if($reciept->merchant_school_amount <= 0)
{
    $reciept->merchant_school_amount = $reciept->merchant_school_amount * (-1);
}
?>
    <div class="clearfix">
        <div>
            <div class="row clearfix">
                <table class="table">
                    <tr>
                        <td style="border-right: 2.5px solid #1E5649; padding-bottom: 20px;">
                            <div class="row clearfix">
                                <div class="col-md-12" style="">
                                <!-- background-color: #24267A; --> 
                                     <div class="item">
                                     @if(isset($company_logo))
                                        @if($company_logo != null)
                                         <center><img src="@if(Request::input('pdf') == 'true'){{public_path().$company_logo}}@else{{$company_logo}}@endif" alt="" style="object-fit: cover; width: 100% " ></center>
                                        @else
                                        <center style="color:white">Please Change Your At Logo Manage Pages Tab.</center>
                                        @endif
                                     @else
                                        <center style="color:white">Please Change Your At Logo Manage Pages Tab.</center>
                                     @endif
                                     </div>
                                    <!-- /assets/front/img/default.jpg -->
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12" style="font-weight: bold;">{{$company_name}}</div>
                                    <div class="col-md-12">Address: {{$shop_address}}</div>
                                    <div class="col-md-12">Phone: {{$shop_contact}}</div>
                                    <div class="col-md-12">Email: {{$company_email}}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="col-md-12" style="margin-left:21px;">
                                    <table class="table" style="background-color: white !important;">
                                        <tr>
                                            <td><img src="@if(Request::input('pdf') == 'true'){{public_path().'/assets/mlm/bongao.png'}} @else {{'/assets/mlm/bongao.png'}}@endif"></td>
                                            <td>
                                                <center><h3>Merchant</h4></center>
                                                <center style="color: red;"><h4>Bongao Discovery School</h4></center>
                                                <center>Datu Halum St., Poblacion, Bongao Tawi-tawi</center>
                                                <center>Contact No.: 068-268-1531</center>
                                            </td>
                                        </tr>
                                    </table>
                                    <span style="font-size: 40px; color:#f1c40f; font-weight: bold;">RECEIPT</span></div>
                                    <div class="col-md-12" style="margin-left:24px;">
                                        Date:
                                        <span class="underlined_text">
                                            {{$reciept->merchant_school_date}}
                                        </span>
                                    </div>
                                    <div class="col-md-12" style="margin-left:24px;">
                                        Invoice #:
                                        <span class="underlined_text_2">
                                            <center>{{$reciept->merchant_school_id}}</center>   
                                        </span>
                                    </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12" style="border-top: 5px solid #1E5649;">
                <div class="tab-content codes_container">
                    <div id="all" class="tab-pane fade in active">
                        <div class="form-group order-tags"></div>
                        <div class="col-md-6">
                            <div class="">
                                <table class="table table-condensed">                        
                                        <tr>
                                            <td class="bill-title col-md-12">V.I.P. <br>Member</td>
                                        </tr>                 
                                        <tr>                               
                                            <td>Name: {{name_format_from_customer_info($reciept)}}</td>
                                        </tr>    
                                        <tr>                             
                                            <td>Slot: {{$reciept->slot_no}}</td>
                                        </tr>   
                                        <tr>                              
                                            <td>Email: {{$reciept->email}}</td>
                                        </tr>                
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <table class="table table-condensed table-bordered">  
                                    <thead>
                                        <th>Name of Student</th>
                                        <th>Student ID No.</th>
                                        <th>Description of School Fees</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$reciept->merchant_school_s_name}}</td>
                                            <td>{{$reciept->merchant_school_s_id}}</td>
                                            <td>{{$reciept->merchant_school_remarks}}</td>
                                            <td>{{$reciept->merchant_school_amount}}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><span class="pull-right">Total</span></td>
                                            <td>{{$reciept->merchant_school_amount}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12" style="margin-top:25px;">
                <table class="table table-condensed tadble">    
                    <tr>
                        <td style="vertical-align: top !important; padding: 0;" colspan="2">
                            <center>Announcement</center>
                            <textarea class="form-control" style="height:135px; resize: none;" disabled>{{$reciept->merchant_school_anouncement}}</textarea>
                        </td>
                        <td style="vertical-align: top !important; padding: 0; border: 0 !important;" colspan="2">
                            <table style="width: 100%; border-top: 0;"> 
                                <tr>
                                    <td colspan="2">
                                        <center>Breakdown</center>
                                    </td>
                                </tr>                 
                                <tr>
                                    <td>Amount Paid Using School Wallet</td>
                                    <td>{{$reciept->merchant_school_amount}}</td>
                                </tr>
                                <tr>
                                    <td>Cash Payment</td>
                                    <td>{{$reciept->merchant_school_cash}}</td>
                                </tr>
                                <tr>
                                    <td>Total Payment</td>
                                    <td>{{$reciept->merchant_school_total_cash}}</td>
                                </tr>
                                <tr>
                                    <td>Remaining School Wallet Balance</td>
                                    <td>{{$reciept->merchant_school_amount_new}}</td>
                                </tr> 
                                <tr>
                                    <td colspan="2">
                                    <center>
                                        <br>
                                        <br>
                                        ______________________________________<br>
                                        Authorized Siginature over printed name
                                    </center>
                                    </td>
                                </tr>                                  
                            </table>
                        </td>
                    </tr>   
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12 text-center">
                <p>-End-</p>
            </div>
        </div>
    </div>       

<style type="text/css">
    .tadble thead tr th
    {
        background-color: #1E5649;
        color: #fff;
    }
    .tadble
    {
        border-collapse: collapse;
    }
    .tadble th, .tadble td
    {
        border: 1px dotted #1E5649 !important;
    }
    .underlined_text
    {
        display: inline-block; 
        line-height: 0.9; 
        border-bottom: 1px dotted; 
        min-width: 159px;
    }

    .underlined_text_2
    {
        display: inline-block; 
        line-height: 0.9; 
        border-bottom: 1px dotted; 
        min-width: 160px;
    }


    .bill-title
    {
        background-color: #BFBFBF;
        text-align: center;
        min-width: 100px;
        font-size: 25px;
        font-weight: bold;
    }

</style>