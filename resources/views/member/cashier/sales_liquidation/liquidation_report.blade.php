<html>
    <head>
        <style type="text/css">
        body
        {
        font-size: 13px;
        font-family: 'Titillium Web',sans-serif;
        }
        </style>
    </head>
    <body>
        <div style="font-size: 13px">
            <div style="page-break-after: always;">
                <div class="text-center" style="top: 450px">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <span style="font-size: 50px;font-weight: bold">SALES REPORT</span>
                    <br>
                    <span style="font-size: 25px;font-weight: bold">SIR# {{sprintf("%'.05d\n", $sir->sir_id)}}</span>
                    <br>
                    <br>
                    <br>
                    <h2>{{strtoupper($sir->first_name)}} {{strtoupper($sir->middle_name)}} {{strtoupper($sir->last_name)}}</h2>
                    <span>AGENT NAME</span>
                    <br>
                    <br>
                    <h2>{{$sir->plate_number}}</h2>
                    <span>Plate Number</span>
                    <br>
                    <br>
                    <h2>{{date('m/d/Y',strtotime($sir->sir_created))}}</h2>
                    <span>DATE</span>
                    <br>
                    <br>
                    <h2>
                    <span style="color: green;font-weight: bold"> CLOSED TRANSACTION</span><br>
                    @if($total_discrepancy != 0)
                    <span style="color: red;font-weight: bold">{{currency('PHP',$total_discrepancy)}}</span>
                    @endif
                    </h2>
                    <span>STATUS</span>
                </div>
            </div>
            <div style="page-break-after: always;">
                <table width="100%">
                    <tr>
                        <td width="60%" style="text-align: left">
                        <h2>Incoming Load Report</h2></td>
                    </tr>
                </table>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row clearfix draggable-container ilr-container">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;" class="text-center">#</th>
                                            <th style="width: 200px;">Product Name</th>
                                            <th style="width: 200px;">Issued QTY</th>
                                            <th style="width: 200px;">Sold QTY</th>
                                            <th style="width: 200px;">Remaining QTY</th>
                                            <th style="width: 200px;">Physical Count</th>
                                            <th style="width: 200px;">Status</th>
                                            <th style="width: 200px;">Info</th>
                                        </tr>
                                    </thead>
                                    <tbody class="{{$loss = 0}} {{$over = 0}} {{$total_sold = 0}} {{$total_issued = 0}}">
                                        @if($_sir_item)
                                        @foreach($_sir_item as $key => $sir_item)
                                        <tr class="tr-draggable tr-draggable-html">
                                            <td class="invoice-number-td text-center">{{$key+1}}</td>
                                            <td>{{$sir_item->item_name}}</td>
                                            <td>{{$sir_item->item_qty}} {{$sir_item->um_abbrev}}</td>
                                            <td>{{$sir_item->sold_qty}}</td>
                                            <td class="total_issued {{$total_issued += (($sir_item->item_qty * $sir_item->um_qty) * $sir_item->sir_item_price)}}">
                                                {{$sir_item->remaining_qty}}
                                            </td>
                                            <td class="total_sold {{$total_sold += ($sir_item->quantity_sold * $sir_item->sir_item_price)}}">
                                                {{$sir->ilr_status == 1 ? '' : $sir_item->physical_count}}
                                            </td>
                                            <td class="loss {{$loss += $sir_item->infos < 0 ? $sir_item->infos : 0}}">
                                                {{$sir_item->status}}
                                            </td>
                                            <td class="over {{$over += $sir_item->infos > 0 ? $sir_item->infos : 0}} text-right">
                                                {{$sir_item->is_updated == 1 ? number_format($sir_item->infos,2) : ''}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6"></td>
                                            <td><strong>TOTAL ISSUED</strong></td>
                                            <td>{{currency('PHP',$total_issued)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td><strong>TOTAL SOLD</strong></td>
                                            <td>{{currency('PHP',$total_sold)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td><strong>LOSS AMOUNT</strong></td>
                                            <td>{{currency('PHP',$loss)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td><strong>OVER AMOUNT</strong></td>
                                            <td>{{currency('PHP',$over)}}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{$empties_loss = 0}} {{$empties_over = 0}} {{$total_return = 0}}"></div>
                @if($ctr_returns != 0)
                <div class="form-group">
                    <div class="col-md-12">
                        <label>EMPTIES</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row clearfix draggable-container empties-container">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;" class="text-center">#</th>
                                            <th style="width: 200px;">Product Name</th>
                                            <th style="width: 200px;">RETURNS QTY</th>
                                            <th style="width: 200px;">Physical Count</th>
                                            <th style="width: 200px;">Status</th>
                                            <th style="width: 200px;">Info</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($_returns)
                                        @foreach($_returns as $keys => $return)
                                        <tr class="tr-draggable tr-draggable-html">
                                            <td class="invoice-number-td text-center">{{$keys+1}}</td>
                                            <td>{{$return->item_name}}</td>
                                            <td>{{$return->item_count}}</td>
                                            <td class="total_sold {{$total_return += ($return->sc_item_qty * $return->sc_item_price)}}">
                                                {{$sir->ilr_status == 1 ? '' : $return->sc_physical_count}}
                                            </td>
                                            <td class="loss {{$empties_loss += $return->sc_infos < 0 ? $return->sc_infos : 0}}">
                                                {{$return->status}}
                                            </td>
                                            <td class="over {{$empties_over += $return->sc_infos > 0 ? $return->sc_infos : 0}} text-right">
                                                {{$return->sc_is_updated == 1 ? number_format($return->sc_infos,2) : ''}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td><strong>TOTAL RETURN</strong></td>
                                            <td>{{currency('PHP',$total_return)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td><strong>LOSS AMOUNT</strong></td>
                                            <td>{{currency('PHP',$empties_loss)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td><strong>OVER AMOUNT</strong></td>
                                            <td>{{currency('PHP',$empties_over)}}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div style="page-break-after: always;">
            
                <div class="{{$total_disc = 0}}"></div>
                @if(count($_inv_dsc) > 0)
                <div>
                    <div class="form-group">
                        <h2>Discount Breakdown</h2>
                    </div>
                    <div class="col-md-12">
                        <div class="row clearfix draggable-container empties-container">
                            <div class="col-sm-12">
                                <table class="digima-table" style="width: 100%">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;" class="text-center">#</th>
                                            <th style="width: 200px;">Customer</th>
                                            <th style="width: 200px;">Transaction Type</th>
                                            <th style="width: 200px;">Transaction No</th>
                                            <th style="width: 200px;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        @foreach($_inv_dsc as $key => $inv_dsc)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$inv_dsc['customer']}}</td>
                                            <td>{{$inv_dsc['transaction_type']}}</td>
                                            <td>{{$inv_dsc['transaction_id']}}</td>
                                            <td class="text-right {{$total_disc += $inv_dsc['amount']}}">{{currency('Php',$inv_dsc['amount'])}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-right">
                                <h4><strong>Total Discount</strong> {{currency('Php',$total_disc)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group">
                    <h3>NO DISCOUNT GIVEN</h3>
                </div>
                @endif

                @if(count($_cm_others) > 0)
                <div>
                    <div class="form-group">
                        <h2>Credit Memo - Others</h2>
                    </div>
                    <div class="col-md-12">
                        <div class="row clearfix draggable-container empties-container">
                            <div class="col-sm-12">
                                <table class="digima-table" style="width: 100%">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;" class="text-center">#</th>
                                            <th style="width: 200px;">Customer</th>
                                            <th style="width: 200px;">Description</th>
                                            <th style="width: 200px;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        @foreach($_cm_others as $key => $cm_others)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$cm_others->customer_name}}</td>
                                            <td>{{$cm_others->description}}</td>
                                            <td class="text-right">{{currency('Php',$cm_others->cm_amount)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-right">
                                <h4><strong>Total CM - OTHERS</strong> {{currency('Php',$total_cm_others)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group">
                    <h3>NO CREDIT MEMO - OTHERS GIVEN</h3>
                </div>
                @endif

                 @if(count($_cm_applied) > 0)
                <div>
                    <div class="form-group">
                        <h2>Credit Memo - Applied</h2>
                    </div>
                    <div class="col-md-12">
                        <div class="row clearfix draggable-container empties-container">
                            <div class="col-sm-12">
                                <table class="digima-table" style="width: 100%">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;" class="text-center">#</th>
                                            <th style="width: 200px;">Customer</th>
                                            <th style="width: 200px;">Description</th>
                                            <th style="width: 200px;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        @foreach($_cm_applied as $key_applied => $cm_applied)
                                        <tr>
                                            <td>{{$key_applied + 1}}</td>
                                            <td>{{$cm_applied->customer_name}}</td>
                                            <td>{{$cm_applied->cm_id}}</td>
                                            <td class="text-right">{{currency('Php',$cm_applied->cm_applied_amount)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 text-right">
                                <h4><strong>Total CM - Applied</strong> {{currency('Php',$total_cm_applied)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group">
                    <h3>NO CREDIT MEMO - APPLIED</h3>
                </div>
                @endif
            </div>
            <div>
                <div class="form-group">
                    <h2>Agent Transaction</h2>
                </div>
                @if($ctr_tr > 0)
                <div>
                    <table class="table table-hover table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <!-- <td class="col-md-2"></td> -->
                                <th>ID</th>
                                <th>Type</th>
                                <th>No</th>
                                <th>Customer Name</th>
                                <th>Due Date</th>
                                <th>Balance</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($_transaction as $transaction)
                            <tr class="cursor-pointer">
                                <td>{{ $transaction['date'] }}</td>
                                <td>{{ $transaction['type'] }}</td>
                                <td>{{ $transaction['no'] }}</td>
                                <td>{{ $transaction['customer_name'] }}</td>
                                <td>{{ $transaction['due_date'] }}</td>
                                <td class="text-right">{{ currency("PHP",$transaction['balance']) }}</td>
                                <td class="text-right">{{ currency("PHP", $transaction['total']) }}</td>
                                <td class="text-center">
                                    <strong>{{$transaction["transaction_code"]}}</strong>
                                </td>
                                <!-- <td>
                                    @if($transaction['reference_name'] == "receive_payment")
                                    <a class="btn btn-default form-control">Closed</a>
                                    @elseif($transaction['status'] == 0)
                                    @if($transaction['reference_name'] == "invoice")
                                    <a class="btn btn-warning form-control">Open</a>
                                    @endif
                                    @elseif($transaction['reference_name'] == "invoice")
                                    @if($transaction['status'] == 1)
                                    <a class="btn form-control" style="background-color: #78C500;color: #fff">Paid</a>
                                    @endif
                                    @endif
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="container text-center">
                    <div class="row vcenter">
                        <div class="col-md-12">
                            <div class="error-template">
                                <h2 class="message">No Trasaction Found</h2>
                                <div class="error-details">
                                    There is no existing transaction for this SIR.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <div class="pull-right">
                        <h4><strong>Cash Sales :</strong> {{currency("Php",$cash_sales)}}</h4>
                    </div>
                </div>
                <br>
                <br>
                <div class="form-group text-right">
                    <div >
                        <h4><strong>Credit Sales:</strong> {{currency("Php",$credit_sales)}}</h4>
                    </div>
                </div>
                <div class="form-group text-right">
                    <div class="pull-right" style="width: 125px;border: 1px solid #000;">
                        
                    </div>
                </div>
                <br>
                <br>
                <div class="form-group text-right">
                    <div >
                        <h4><strong>Total:</strong> <span style="border-bottom: double">{{currency("Php",$cash_sales + $credit_sales)}}</span></h4>
                    </div>
                </div>
                <br>
                <br>
                <br>
            </div>

        </div>
          <div>
                <br>
                <br>
                <br>
                <br>
                <div class="form-group text-center">
                    <table class="text-center" style="width: 100%;font-size: 12px;">
                        <tr>
                            <td style="width: 33%">{{currency('PHP', $total_sold - $total_disc)}}</td>
                            <td style="width: 33%">{{currency('PHP', $total_return)}}</td>
                            <td style="width: 33%">{{currency('PHP', $total_ar)}}</td>
                        </tr>
                        <tr>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                        </tr>
                        <tr>
                            <td style="width: 33%">TOTAL SALES</td>
                            <td style="width: 33%">TOTAL EMPTIES</td>
                            <td style="width: 33%">ACCOUNTS RECEIVABLE</td>
                        </tr>
                    </table>
                </div>
                <br>
                <br>
                <br>
                <div class="form-group text-center">
                    <table class="text-center" style="width: 100%;font-size: 12px;">
                        <tr>
                            <td style="width: 33%">{{currency('PHP', $ar_collection)}}</td>
                            <td style="width: 33%">{{currency('PHP', $total_cm_applied)}}</td>
                            <td style="width: 33%">{{currency('PHP', $total_cm)}}</td>
                        </tr>
                        <tr>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                        </tr>
                        <tr>
                            <td style="width: 33%">TOTAL AR COLLECTION</td>
                            <td style="width: 33%">TOTAL CREDIT MEMO APPLIED</td>
                            <td style="width: 33%">CREDIT MEMO ISSUED</td>
                        </tr>
                    </table>
                </div>

                <br>
                <br>
                <div class="form-group text-center">
                    <table class="text-center" style="width: 33%;font-size: 12px;">
                        <tr>
                            <td style="width: 33%">{{currency('PHP', $total_cm_others)}}</td>
                        </tr>
                        <tr>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                        </tr>
                        <tr>
                            <td style="width: 33%">CREDIT MEMO - OTHERS ISSUED</td>
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <div>

                        <table class="text-center" style="width: 100%;font-size: 12px;" {{$cm_applied = 0}}>
                            <tr>
                                <td style="width: 40%"><h4><strong>AMOUNT TO BE REMITTED : </strong></h4></td>
                                <td style="width: 60%" {{ $sales = $total_sold - $total_disc }}>
                                    <div style="border-bottom: 1px solid #000;width: 100%" class="{{$total_amount_tobe_remitted = (((($sales - $total_return) - $total_ar) - $total_cm_applied) +  $ar_collection) + $total_cm }}">
                                        <h4>{{currency('PHP', $total_amount_tobe_remitted)}} </h4>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <table class="text-center" style="width: 100%;font-size: 12px;">
                            <tr>
                                <td style="width: 40%"><h4><strong>AMOUNT REMITTED : </strong></h4></td>
                                <td style="width: 60%" >
                                    <div style="border: 1px solid #000;width: 100%;padding: 20px">
                                        <h4>{{currency('PHP', $rem_amount)}} </h4>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 40%"><h4><strong>COLLECTION REMARKS : </strong></h4></td>
                                <td style="width: 60%" >
                                    <div style="border-bottom: 1px solid #000;width: 100%;">
                                        <h4>{!! $rem_remarks !!}</h4>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div class="form-group text-center" {{$agent_descrepancy = ($rem_amount - $total_amount_tobe_remitted)}}>
                    <table class="text-center {{$total_loss = $loss + $empties_loss}} {{$total_over = $over + $empties_over}}" style="width: 100%;font-size: 12px;">
                        <tr>
                            <td style="width: 33%">{{currency('PHP', round($agent_descrepancy))}}</td>
                            <td style="width: 33%">{{currency('PHP', $total_loss)}}</td>
                            <td style="width: 33%">{{currency('PHP', $total_over)}}</td>
                        </tr>
                        <tr>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                        </tr>
                        <tr>
                            <td style="width: 33%">AGENT DISCREPANCY</td>
                            <td style="width: 33%">TOTAL LOSS</td>
                            <td style="width: 33%">TOTAL OVER</td>
                        </tr>
                    </table>
                </div>

                <br>
                <br>
                <br>
                <div class="form-group">
                    <table class="text-center" style="width: 100%;font-size: 13px;">
                        <tr>
                            <td style="width: 33%"></td>
                            <td style="width: 33%"></td>
                            <td style="width: 33%">{{strtoupper($user->user_first_name." ".$user->user_last_name)}}</td>
                        </tr>
                        <tr>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                            <td><div style="border-bottom: 1px solid #000;width: 100%"></div></td>
                        </tr>
                        <tr>
                            <td style="width: 33%">Approved By:</td>
                            <td style="width: 33%">Checked By:</td>
                            <td style="width: 33%">Printed By:</td>
                        </tr>
                    </table>
                </div>
                <br>
                <br>
                <br>
                <br>
                <div class="form-group">
                    <table style="width: 100%;font-size: 13px;">
                        <tr>
                            <td style="width: 33%">Conforme :</td>
                        </tr>
                        <tr>
                            <td>
                                <br>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td><div style="border-bottom: 1px solid #000;width: 33%"></div></td>
                        </tr>
                        <tr>
                            <td style="width: 33%; font-style: italic;">
                                <div>
                                            I hereby attest to the truth of the transaction I made, I am liable
                                    for all the losses that I incurred. In case the collection of all my losses
                                    is made thru court. I will pay Jose L. Tiong - owner, 45% per annum interest plus attorney's
                                    fees and reimbursed all incidental expenses incurred by the owner of the company
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
    </body>
    <style type="text/css">
        table
        {
            border-collapse: collapse;
        }
        tr th
        {
            padding: 5px;
            border: 1px solid #000;
        }
        tr td
        {
            padding: 5px;
        }

        div 
        {
            page-break-inside: avoid; 
        }
    </style>
</html>