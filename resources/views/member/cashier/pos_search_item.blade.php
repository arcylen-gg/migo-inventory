@if(count($_item) > 0)
    <div class="pos-result-header">
        Search result for <i>"{{ $keyword }}"</i>
    </div>
    <div class="pos-item-list">
        @foreach($_item as $key => $item)
        <div class="pos-item pos-item-search-result" item_id="{{ $item->item_id }}">
            <div class="row">
                <div class="col-md-2">
                    <div class="item-image text-center"><img src="/assets/member/images/item.png"></div>
                </div>
                <div class="col-md-10">
                    <div class="item-info">
                        <div class="item-name text-bold">{{ $item->item_name }}</div>
                        <div class="item-sku">{{ $item->item_sku }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="no-item-found"><i class="fa fa-search search-icon"></i> NO ITEM MATCHED YOUR KEYWORD <i>"{{ $keyword }}"</i> </div>
@endif