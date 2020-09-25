
    <!-- NO PRODUCT YET -->
    <div class="clearfix">
        <div>
            <div class="row clearfix">

                <table class="table">
                    <tr>
                        <td style="border-right: 2.5px solid #1E5649; padding: 0;">
                            <div class="row clearfix">
                                 <div class="col-md-8">
                                    <div class="col-md-6">
                                    <!-- style="background-color: #24267A;" -->
                                    @if($pdf == 'true')
                                        @if($company_logo != null)
                                             <div class="item" style="background-image: url('{{public_path().$company_logo}}'); background-size: contain; width: 100%;height: 75px; background-repeat: no-repeat; background-position: center;   ">
                                                 <!-- <center><img src="@if(Request::input('pdf') == 'true'){{public_path().$company_logo}} @else {{$company_logo}}@endif" alt="" style="object-fit: cover; width: 100%;height: 50px  "></center> -->
                                             </div>
                                        @else 
                                            <div class="item" style="background-image: url('{{public_path().'/assets/philtech-official-logo.png'}}'); background-size: contain; width: 100%;height: 75px; background-repeat: no-repeat; background-position: center;    ">
                                                 <!-- <center><img src="@if(Request::input('pdf') == 'true'){{public_path().'/assets/philtech-official-logo.png'}} @else {{'/assets/philtech-official-logo.png'}}@endif" alt="" style="object-fit: cover; width: 100%; height: 50px; " ></center> -->
                                             </div>
                                        @endif 
                                    @else
                                        @if($company_logo != null)
                                             <div class="item" style="background-image: url('{{$company_logo}}'); background-size: contain; width: 100%;height: 75px; background-repeat: no-repeat; background-position: center;   ">
                                                 <!-- <center><img src="@if(Request::input('pdf') == 'true'){{public_path().$company_logo}} @else {{$company_logo}}@endif" alt="" style="object-fit: cover; width: 100%;height: 50px  "></center> -->
                                             </div>
                                        @else 
                                            <div class="item" style="background-image: url('{{'/assets/philtech-official-logo.png'}}'); background-size: contain; width: 100%;height: 75px; background-repeat: no-repeat; background-position: center;    ">
                                                 <!-- <center><img src="@if(Request::input('pdf') == 'true'){{public_path().'/assets/philtech-official-logo.png'}} @else {{'/assets/philtech-official-logo.png'}}@endif" alt="" style="object-fit: cover; width: 100%; height: 50px; " ></center> -->
                                             </div>
                                        @endif 
                                    @endif   
                                    </div>
                                    <div class="col-md-6" style="font-size: 8px;">
                                        <div class="col-md-12" style="font-weight: bold;">{{$company_name}}</div>
                                        <div class="col-md-12">Address: {{$shop_address}}</div>
                                        <div class="col-md-12">Phone: {{$shop_contact}}</div>
                                        <div class="col-md-12">Email: {{$company_email}}</div>
                                    </div>
                                 </div>   

                                <div class="col-md-4" style="border-left: 1px dashed;">
                                    <div class="col-md-12" >
                                        <span style="font-size: 16px; color:#f1c40f; font-weight: bold;">RECEIPT</span>
                                    </div>
                                    <div class="col-md-12" style=" font-size: 8px;" >
                                        Date:
                                        <span class="underlined_text" style="width: 100%">
                                            <center>{{$invoice->item_code_date_created}}</center>
                                        </span>
                                    </div>
                                    <div class="col-md-12" style="font-size: 8px;" >
                                        Invoice #:
                                        <span class="underlined_text_2" style="width: 100%">
                                        @if($invoice->item_code_invoice_number == null)
                                            
                                            <center>{{$invoice->item_code_invoice_id}}</center>
                                        @else
                                            
                                            <center>{{$invoice->item_code_invoice_number}}</center>
                                        @endif    
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="tab-content codes_container">
                    <div id="all" class="tab-pane fade in active">
                        <!-- <div class="form-group order-tags"></div> -->
                        <div class="table-responsive">
                            
                            <table class="table table-condensed">                        
                                    <tr style="background-color: #ddece9" >
                                        <td rowspan="4" class=" col-md-4" style="font-size: 16px; vertical-align: middle; color: #1E5649"><b><center>V.I.P. Member</center></b></td>
                                    </tr>                        
                                    <tr>                                
                                        <td style="font-size: 10px;">Name: {{name_format_from_customer_info($invoice)}}</td>
                                    </tr>
                                    @if(isset($slot->slot_no))
                                    <tr>
                                        <td style="font-size: 10px;">Slot: {{$slot->slot_no}}</td>
                                    </tr>   
                                    @endif
                                    <tr>                                
                                        <td style="font-size: 10px;">Email:{{$invoice->email}}</td>
                                    </tr>    
                                                     
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix" >
            <div class="col-md-12" >
                <table class="table table-condensed tadble">     
                    <thead>                   
                            <tr style="font-size: 10px;">
                                <th>ITEM NAME</th>
                                <th>CODE</th>
                                <th>UNIT PRICE</th>
                                <th>QUANTITY</th>
                                <th>ORIGINAL PRICE</th>
                                <th>VIP PRICE</th>
                            </tr>   
                    </thead>    
                    <tbody style="font-size: 10px;">
                    @if(isset($item_list))
                        @foreach($item_list as $key => $value)
                        <tr>
                            <td>{{$value->item_name}}</td>
                            <td>{{$value->item_activation_code}}</td>
                            <td>{{$value->item_price}}</td>
                            <td>{{$value->item_quantity}}</td>
                            <td>{{$value->item_price * $value->item_quantity}}</td>
                            <td>{{$value->item_membership_discounted * $value->item_quantity}}</td>
                        </tr>
                        @if($value->item_serial != null)
                        <tr>
                            <td colspan="2"><span class="pull-right">Serial/s:</span></td>
                            <td colspan="3">{{$value->item_serial}}</td>
                        </tr>
                        @endif
                        @endforeach
                    @else
                    <tr>
                        <td colspan="40"><center></center></td>
                    </tr>    
                    @endif    
                    </tbody>               
                </table>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12" >
                <table class="table table-condensed tadble" style="font-size: 10px;">     
                    <thead>                   
                            <tr class="hide">
                                <th>Code ID</th>
                                <th>Activation Code</th>
                                <th>Used on slot</th>
                                <th>Date Used</th>
                            </tr>   
                    </thead>   
                    <tbody>
                        <?php $sum_points = 0; ?>
                        @foreach($_code as $code)
                            <tr class="hide">                                
                                <td>{{$code->item_code_id}}</td>
                                <td>{{$code->item_activation_code}}</td>
                                <td>{{$code->slot_no}}</td>
                                <td>{{$code->date_used}}</td>
                            </tr>    
                            <?php if(isset($code->REPURCHASE_POINTS)){ 
                                $sum_points += $code->REPURCHASE_POINTS;
                            }  ?>
                        @endforeach
                        <tr>
                            <td style="vertical-align: top !important; padding: 0; background-color: #ddece9" colspan="2">
                                <textarea class="form-control" style="height: 100%; resize: none;" disabled>
                                    {{$invoice->item_code_statement_memo}}
                                </textarea>
                            </td>
                            <td style="vertical-align: top !important; padding: 0; border: 0 !important;" colspan="2">
                                <table style="width: 100%; border-top: 0;">  
                                    @if($sum_points != 0)
                                    <tr>
                                        <td>Total Points (Repurchase): </td>
                                        <td>{{$sum_points}}</td>
                                    </tr>                
                                    @endif 
                                    <tr>
                                        <td>Subtotal</td>
                                        <td>{{$subtotal}}</td>
                                    </tr>   
                                    <tr>                                
                                        <td>Discount</td>
                                        <td>{{$discount_amount}}</td>
                                    </tr>   
                                    <tr>                                
                                        <td>Total</td>
                                        <td>{{$total}}</td>
                                    </tr> 

                                    <tr>
                                        <td colspan="2">
                                        <br>
                                        </td>
                                    </tr>     
                                    <tr>
                                        <td>Payment :</td>
                                        <td>
                                            <?php
                                            $label = 'Tendered Amount';
                                            $label_change = 'Change';
                                            switch ($invoice->item_code_payment_type) {
                                                case 1:
                                                    echo 'Cash'; 
                                                    break;
                                                case 2:
                                                    echo 'GC';
                                                    $label = 'GC Amount';
                                                    $label_change = 'GC Change';
                                                    break;  
                                                case 3:
                                                    echo 'Wallet';
                                                    $label = 'Current Wallet';
                                                    $label_change = 'Remaining Wallet';
                                                    break;   
                                                case 4: 
                                                    echo 'Vmoney';
                                                    break;         
                                                default:
                                                    echo 'Cash';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{$label}} :</td>
                                        <td>{{number_format($invoice->item_code_tendered_payment, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{$label_change}} :</td>
                                        <td>{{number_format($invoice->item_code_change, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                        <center>
                                            <br>
                                            ______________________________________<br>
                                            Authorized Signature over printed name
                                        </center>
                                        </td>
                                    </tr>                                 
                                </table>
                            </td>
                        </tr>                    
                    </tbody>                  
                </table>
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