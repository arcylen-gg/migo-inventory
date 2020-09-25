@if(count($_customer) > 0)
    <div class="pos-result-customer-header">
        Search result for <i>"{{ $keyword }}"</i>
    </div>
    <div class="pos-customer-list">
        @foreach($_customer as $key => $customer)
        <div class="pos-customer pos-customer-search-result" customer_id="{{ $customer->customer_id }}">
            <div class="row">
                <div class="col-md-2">
                    &nbsp;
                </div>
                <div class="col-md-10">
                    <div class="customer-info">
                        <div class="customer-name text-bold">{{ $customer->first_name . ' ' . $customer->middle_name . ' '. $customer->last_name }}</div>
                        <div class="customer-sku">{{ $customer->company }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="no-customer-found"><i class="fa fa-search search-icon"></i> NO CUSTOMER MATCHED YOUR KEYWORD <i>"{{ $keyword }}"</i> </div>
@endif