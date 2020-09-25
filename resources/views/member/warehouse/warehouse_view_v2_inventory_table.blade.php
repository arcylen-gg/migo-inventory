 <div class="form-group tab-content panel-body inventory-container">
    <div id="bundle" class="bundle tab-pane fade in active">
        <div class="form-group order-tags"></div>
        <div class="table-responsive">
            @include("member.load_ajax_data.load_bundle_item")
        </div>
    </div>
    <div id="inventory" class="inventory tab-pane fade">
        <div class="form-group order-tags"></div>
        <div class="table-responsive">
            @include("member.load_ajax_data.load_bundle_item_inventory")
        </div>
    </div>
    <div id="bundle_empties" class="bundle tab-pane fade">
        <div class="form-group order-tags"></div>
        <div class="table-responsive">
            @include("member.load_ajax_data.load_bundle_item",['warehouse_item_bundle' => $warehouse_item_bundle_empties])
        </div>
    </div>
    <div id="empties" class="empties tab-pane fade">
        <div class="form-group order-tags"></div>
        <div class="table-responsive">
            @include("member.load_ajax_data.load_bundle_item_inventory_empties")
        </div>
    </div>
</div>