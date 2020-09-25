<div class="transaction-class">
    <div class="row cleafix">
        @if($purchase_requisition)
        <div class="col-md-2 col-md-offset-1 po-transaction-class ">
            <a href="/member/transaction/purchase_order"> 
                <span class="span-amount">{{$po_amount}}</span><br>
                <span>{{$count_po == 0 ? 0 : $count_po}} </span> Open Purchase Orders
            </a>
        </div>
        <div class="col-md-2 pr-transaction-class ">
            <a href="/member/transaction/purchase_requisition"> 
                <span class="span-amount">{{$pr_amount}}</span><br>
                <span>{{$count_pr == 0 ? 0 : $count_pr}}  Open Requisitions</span>
            </a>
        </div>
        <div class="col-md-2 so-transaction-class ">
            <a href="/member/transaction/sales_order"> 
                <span class="span-amount">{{$so_amount}}</span><br>
                <span>{{$count_so == 0 ? 0 : $count_so}}  Open Sales Orders</span>
            </a>
        </div>
        <div class="col-md-2 ap-transaction-class ">
            <a href="/member/transaction/enter_bills"> 
               <span class="span-amount">{{$ap_amount}}</span><br>
               <span>{{$count_ap == 0 ? 0 : $count_ap}}  Accounts Payables</span>
            </a>
        </div>
        <div class="col-md-2 ar-transaction-class ">
            <a href="/member/transaction/sales_invoice"> 
               <span class="span-amount">{{$ar_amount}}</span><br>
               <span>{{$count_ar == 0 ? 0 : $count_ar}}  Accounts Receivables</span>
            </a>
        </div>
        @else

        <div class="col-md-3 po-transaction-class ">
            <a href="/member/transaction/purchase_order"> 
                <span class="span-amount">{{$po_amount}}</span><br>
                <span>{{$count_po == 0 ? 0 : $count_po}} </span> Open Purchase Orders
            </a>
        </div>
        <div class="col-md-3 so-transaction-class ">
            <a href="/member/transaction/sales_order"> 
                <span class="span-amount">{{$so_amount}}</span><br>
                <span>{{$count_so == 0 ? 0 : $count_so}}  Open Sales Orders</span>
            </a>
        </div>
        <div class="col-md-3 ap-transaction-class ">
            <a href="/member/transaction/enter_bills"> 
               <span class="span-amount">{{$ap_amount}}</span><br>
               <span>{{$count_ap == 0 ? 0 : $count_ap}}  Accounts Payables</span>
            </a>
        </div>
        <div class="col-md-3 ar-transaction-class ">
            <a href="/member/transaction/sales_invoice"> 
               <span class="span-amount">{{$ar_amount}}</span><br>
               <span>{{$count_ar == 0 ? 0 : $count_ar}}  Accounts Receivables</span>
            </a>
        </div>
        @endif
    </div>
</div>