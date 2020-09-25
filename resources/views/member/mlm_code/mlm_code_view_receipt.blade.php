
    <!-- NO PRODUCT YET -->
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
                                         <center><img src="@if(Request::input('pdf') == 'true'){{public_path().$company_logo}} @else {{$company_logo}}@endif" alt="" style="object-fit: cover; width: 100% " ></center>
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
                                <span style="font-size: 40px; color:#f1c40f; font-weight: bold;">RECEIPT</span></div>
                                <div class="col-md-12" style="margin-left:24px;">
                                    Date:
                                    <span class="underlined_text">
                                        {{$invoice->membership_code_date_created}}
                                    </span>
                                </div>
                                <div class="col-md-12" style="margin-left:24px;">
                                    Invoice #:
                                    <span class="underlined_text_2">
                                    @if($invoice->item_code_invoice_number == null)
                                        
                                        <center>{{$invoice->membership_code_invoice_id}}</center>
                                    @else
                                        
                                        <center>{{$invoice->item_code_invoice_number}}</center>
                                    @endif    
                                    </span>
                                </div>
                        </td>
                    </tr>
                </table>
               <!--  <div class="col-md-8" style="border-right: 2.5px solid #1E5649; padding-bottom: 20px;">
                    <div class="row clearfix">
                        <div class="col-md-3">
                             <div class="item">
                                <img style="width: 100%;" src="@if(Request::input('pdf') == 'true'){{$company_logo}}@endif" alt="">
                             </div>
                        </div>
                        <div class="col-md-9">
                            <div class="col-md-12" style="font-weight: bold;">{{$company_name}}</div>
                            <div class="col-md-12">Address: {{$shop_address}}</div>
                            <div class="col-md-12">Phone: {{$shop_contact}}</div>
                            <div class="col-md-12">Email: {{$company_email}}</div>
                        </div>
                    </div>
                </div>    
                <div class="col-md-4">
                    <div class="col-md-12" style="margin-left:21px;">
                    <span style="font-size: 40px; color:#f1c40f; font-weight: bold;">RECEIPT</span></div>
                    <div class="col-md-12" style="margin-left:24px;">
                        Date:
                        <span class="underlined_text">
                            {{$invoice->membership_code_date_created}}
                        </span>
                    </div>
                    <div class="col-md-12">
                        Invoice #:
                        <span class="underlined_text_2">
                        @if($invoice->item_code_invoice_number == null)
                            
                            <center>{{$invoice->membership_code_invoice_id}}</center>
                        @else
                            
                            <center>{{$invoice->item_code_invoice_number}}</center>
                        @endif    
                        </span>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12" style="border-top: 5px solid #1E5649;">
                <div class="tab-content codes_container">
                    <div id="all" class="tab-pane fade in active">
                        <div class="form-group order-tags"></div>
                        <div class="table-responsive">
                            <table class="table table-condensed">                        
                                    <tr>
                                        <td rowspan="4" class="bill-title col-md-2">V.I.P. Member</td>
                                    </tr>                        
                                    <tr>                                
                                        <td>Name:{{$invoice->membership_code_invoice_f_name}} {{$invoice->membership_code_invoice_m_name}} {{$invoice->membership_code_invoice_l_name}}</td>
                                    </tr>
                                    <tr>                                
                                        <td>Email:{{$invoice->membership_code_customer_email}}</td>
                                    </tr>                        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12" style="margin-top:25px;">
                <table class="table table-condensed tadble">     
                    <thead>                   
                            <tr>
                                <th>Code ID</th>
                                <th>Activation Code</th>
                                <th>Code Type</th>
                                <th>Package</th>
                            </tr>   
                    </thead>   
                    <tbody>
                        @foreach($_code as $code)
                            <tr>                                
                                <td>{{$code->membership_code_id}}</td>
                                <td>{{$code->membership_activation_code}}</td>
                                <td>{{$code->membership_type}}</td>
                                <td>{{$code->membership_package_name}}</td>
                            </tr>    
                        @endforeach
                        <tr>
                            <td style="vertical-align: top !important; padding: 0;" colspan="2">
                                <textarea class="form-control" style="height:135px; resize: none;" disabled>
                                    {{$invoice->membership_code_statement_memo}}
                                </textarea>
                            </td>
                            <td style="vertical-align: top !important; padding: 0; border: 0 !important;" colspan="2">
                                <table style="width: 100%; border-top: 0;">                  
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
                                        <center>
                                            <br>
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