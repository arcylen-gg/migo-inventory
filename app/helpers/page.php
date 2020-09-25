<?php

function page_list($pis = 0)
{
    $path = '/member/';

    $page = "dashboard";
    $nav[$page]['name']        = "Dashboard";
    $nav[$page]['segment']      = $page;
    $nav[$page]['icon']         = "home";
    $nav[$page]['url']          = $path;
    $nav[$page]['user_settings'] = [];
    $nav[$page]['status']       = "Working 100% - Not Tested Yet";
    $nav[$page]['developer']    = "Everyone";

    /* REPORTS  */
    $page = "report"; 
    $nav[$page]['name']     = "Reports";
    $nav[$page]['segment']  = $page;
    $nav[$page]['icon']     = "area-chart";
    $nav[$page]['type']     = "menu";

    /* -- REPORT => ECOMMERCE SUBMENU */
    $segment = "submenu-ecommerce";
    $nav[$page]['submenu'][$segment]['name']   = "Ecommerce";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        /* -- REPORT => PRODUCTS  */
        $code = "report-product-sales";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Product Sales";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/sale/product";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Everyone";

        /* -- REPORT => MONTHLY PRODUCT  */
        $code = "report-product-sales-monthly";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Product Sales Monthly";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/sale/month";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Everyone";

        /* -- REPORT => PRODUCTS VARIANT  */
        $code = "report-product-variant-sales";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']        = "Product Variant Sales";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']         = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']          = $path . $page . "/sale/product_variant";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $segment2 = "submenu-SampleSubMenu2";
        // $nav[$page]['submenu'][$segment]['submenu'][$segment2]['name']   = "SampleSubMenu2";
        // $nav[$page]['submenu'][$segment]['submenu'][$segment2]['segment']= $segment2;
        // $nav[$page]['submenu'][$segment]['submenu'][$segment2]['type']   = "submenu";

        //     $segment3 = "submenu-SampleSubMenu3";
        //     $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['name']   = "SampleSubMenu3";
        //     $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['segment']= $segment3;
        //     $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['type']   = "submenu";

        //         $code = "report-sasafsdfsdf";
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['label']        = "SamplePage1";
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['code']         = $code;
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['url']          = '';
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['user_settings'] = ['access_page'];
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['status']        = "Developing";
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['developer']     = "Everyone";

        //         $code = "reportdfgdfgf";
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['label']        = "SamplePage2";
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['code']         = $code;
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['url']          = '';
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['user_settings'] = ['access_page'];
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['status']        = "Developing";
        //         $nav[$page]['submenu'][$segment]['submenu'][$segment2]['submenu'][$segment3]['submenu'][$code]['developer']     = "Everyone";

    


    /* -- REPORT => MLM */
    $segment = "submenu-loyalty-reward";
    $nav[$page]['submenu'][$segment]['name']   = "Loyalty Reward System"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        $code = "report-reward-brown-rank";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Brown Ranking";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/reward-brown-rank";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Guilermo Tabligan";

        $code = "report-logistic";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Logistic Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/logistic";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

    /* -- REPORT => AGENT TRANSACTIONS */
    $code = "report-agent-transaction";
    $nav[$page]['submenu'][$code]['label'] = "Agent Profit & Loss";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/agent/profit_loss";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "ARCY";

    /* -- REPORT => ACCOUNTING SUBMENU */
    $segment = "submenu-accounting";
    $nav[$page]['submenu'][$segment]['name']   = "Accounting"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        /*$code = "report-stock-ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Stock Ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/stock_ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";*/
        
        

        $code = "report-profit-and-loss-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Profit and Loss";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/profit_loss";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "report-general-ledger-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "General Ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/general/ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";
        
        $code = "report-account-list-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Account List";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/account_list";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Bryan Kier Aradanas";

        $code = "report-item-price-change";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales & Cost Price History";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales_cost_price_history";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";


    /* -- REPORT => Warehouse SUBMENU */
    $segment = "submenu-warehouse";
    $nav[$page]['submenu'][$segment]['name']   = "Warehouse";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        $code = "report-warehouse";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Warehouse Issuance Slip Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/warehouse/wis";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        /* -- INVENTORY => WAREHOUSE TRANSFER  */
        $code = "report-warehouse-transfer";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Warehouse Transfer";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/warehouse/transfer";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

    /* -- REPORT => INVENTORY SUBMENU */
    $segment = "submenu-inventory";
    $nav[$page]['submenu'][$segment]['name']   = "Inventory"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        $code = "report-item-list-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Item List";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/item_list";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "report-inventory-summary";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory Valuation Summary";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/inventory/summary";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "quantity-on-hand-by-site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Quantity on Hand by Site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/quantity/onhand/by/site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-inventory-summary-by-site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory Valuation Summary by Site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/inventory/summary/by/site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-inventory-stock-status-by-item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory Stock Status by Item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/inventory/stock/status/by/item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-inventory-stock-status-by-vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory Stock Status by Vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/inventory/stock/status/by/vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-inventory-stock-status-by-site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory Stock Status by Site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/inventory/stock/status/by/site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-assembly-shortage-by-item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Assembly Shortage by Item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/assembly/shortage/by/item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-assembly-shortage-by-vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Assembly Shortage by Vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/assembly/shortage/by/vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-assembly-shortage-by-site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Assembly Shortage by Site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/assembly/shortage/by/site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-physical-inventory-worksheet";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Physical Inventory Worksheet";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/physical/inventory/worksheet";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-pending-builds";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Pending Builds";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/pending/builds";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-pending-builds-by-site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Pending Builds by Site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/pending/builds/by/site";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-negative-item-listing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Negative Item Listing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/negative/item/listing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";
        
        $code = "report-sales-best-seller";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Best Seller Reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/sale/best_seller_item";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "report-bin-location";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Bin Location Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/bin_location";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

    $segment = "purchases-report";
    $nav[$page]['submenu'][$segment]['name']   = "Purchases"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";   

        $code = "open-purchase-orders";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Open Purchase Orders";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/open/purchase/order";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone"; 

        $code = "purchase-orders-by-vendor";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchase Orders by Vendor";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/vendor_purchase_order";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone"; 

        // $code = "purchases-by-vendor-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchases by Vendor Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/purchases/by/vendor/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "purchases-by-vendor-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchases by Vendor Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/purchases/by/vendor/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "purchases-by-item-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchases by Item Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/purchases/by/item/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";
    
        // $code = "purchases-by-item-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchases by Item Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/purchases/by/item/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";


        // $code = "open-purchase-orders/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Open Purchase Orders Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/open/purchase/orders/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";


        // $code = "report-purchasesvendor-report";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Open Purchase Order by Vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/open/purchase/vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-purchasesvendorsummary-report";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Open Purchase Order by Vendor Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/open/purchase/vendor_summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone"; 

        // $code = "open-purchase-orders/by/jobs";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Open Purchase Orders by Jobs";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/open/purchase/orders/by/jobs";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-purchases-report";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Open Purchase Order by Item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/open/purchase/order";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";
        
        

    $segment = "sales-report";
    $nav[$page]['submenu'][$segment]['name']   = "Sales"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        // $code = "report-sales-by-customer-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales by Customer Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/by/customer/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-sales-by-customer-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales by Customer Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/by/customer/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-sales-by-item-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales by Item Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/by/item/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-sales-by-item-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales by item Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/by/item/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-sales-by-ship-to-address";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales by Ship to Address";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/by/ship/to/address";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-pending-sales";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Pending Sales";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/pending/sales";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-sales-orders-by-customer";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Orders by Customer";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/orders/by/customer";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-sales-orders-by-item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Orders by Item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/orders/by/item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "report-sales-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales By Customer";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/sale";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "report-sales-item-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales By Item";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/sale/item";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        
        $code = "gain-by-invoice";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales with Bank Interest";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales_bank_interest";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "report-sales-item-warehouse";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales By Warehouse";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/sale_by_warehouse";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

                
        $code = "report-sales-best-seller";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Best Seller Reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/sale/best_seller_item";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "report-sales-representative";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Report per Sales Representative";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales_representative";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "gain-by-item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Gain by Item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales_gain_item";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

    $segment = "vendors-and-payables-report";
    $nav[$page]['submenu'][$segment]['name']   = "Vendors and Payables"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        $code = "report-vendor-list-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Vendor List";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/vendor_list";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Bryan Kier Aradanas";

        // $code = "ap-aging-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "A/P Aging Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/ap/aging/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "ap-aging-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "A/P Aging Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/ap/aging/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "vendor-balance-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Vendor Balance Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/vendor/balance/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "vendor-balance-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Vendor Balance Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/vendor/balance/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "unpaid-bills-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Unpaid Bills Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/unpaid/bills/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "accounts-payables-graph";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Accounts Payables Graph";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounts/payables/graph";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "transaction-list-by-vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Transaction List by Vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/transaction/list/by/vendor";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "1099-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "1099 Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/1099/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "1099-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "1099 Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/1099/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "sales-tax-liability";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Tax Liability";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/tax/liability";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "sales-tax-revenue-summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Tax Revenue Summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sales/tax/revenue/summary";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "vendor-phone-list";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Vendor Phone List";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/vendor/phone/list";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        // $code = "vendor-list-account";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Vendor Contact List";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/vendor/contact/list";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        
    $segment = "customers-and-receivables-report";
    $nav[$page]['submenu'][$segment]['name']   = "Customers and Receivables"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";


        $code = "report-customer-list";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Customers List";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/customer_list";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";


        // $code = "report-customer-and-receivables";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Customers and Receivables";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/customer/and/receivables";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        // $code = "report-ar-aging-detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "A/R Aging Detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/ar/aging/detailed";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        // $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "report-sales-best-seller";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Best Seller Reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/sale/best_seller_item";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

    $segment = "customized-report";
    $nav[$page]['submenu'][$segment]['name']   = "Customized Report"; 
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        $code = "daily-sales-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Daily Sales Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/daily_sales_report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Edrich Bonifacio";

        $code = "monthly-sales-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Monthly Sales Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/monthly_sales_report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Edrich Bonifacio";

        $code = "customer-auto-updates-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Customer Auto Update Reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/customer_auto_updates";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "top-customer";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Top Customer";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/top_customer";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "account-receivable";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Accounts Receivable";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounts_receivable";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "account-payable";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Accounts Payable";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounts_payable";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "retain-credit-reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Retain Credit by Customer Reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/retain_credit";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "customer-equipment-monitoring-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Equipment Monitoring Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/equipment_report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        
        $code = "report-item-status";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Item Status";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/item_status";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "report-sales-item-vendor-category";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchases By Item By Vendor";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/purchase/item_vendor_category";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "best-seller-report-item-name";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Best Seller Report By Item Name";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/accounting/sale/best_seller_item_by_pattern";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Jeff Masangkay";

        $code = "inventory-adjustment-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory Adjustment Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/adjustment/summary";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Jeff Masangkay";

        $code = "dyandcy-daily-sales";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Dy and Cy Daily Sales";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/dyandcy/dailysales";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "dyandcy-income-per-month";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Dy and Cy Income per month";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/dyandcy/incomepermonth";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "dyandcy-weekly-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Weekly Sales report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/dyandcy/weeklyreport";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "dyandcy-monthly-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Monthly Sales report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/dyandcy/monthlyreport";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

        $code = "dyandcy-annual-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Annual Sales report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/dyandcy/annualreport";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Arcylen Gutierrez";


        /* -- INVENTORY => INVENTORY DETAILED  */
       /* $code = "report-inventory-detailed";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory - Detailed";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/inventory/detailed";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";*/

    /* TRANSACTION */
    // $page = "transaction_list";  
    // $nav[$page]['name'] = "Transaction";
    // $nav[$page]['segment'] = $page;
    // $nav[$page]['icon'] = "refresh";
    /* -- TRANSACTION => CREATE INVOICE */
    // $code = "transaction-invoice-list";
    // $nav[$page]['submenu'][$code]['label'] = "Customer Invoice";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = "$path" . "customer" . "/invoice_list";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Refer to Customer -> Create Invoices";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    // /* -- TRANSACTION => RECEIVE PAYMENT */
    // $code = "transaction-receive-payment";
    // $nav[$page]['submenu'][$code]['label'] = "Receive Payment";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . "customer" . "/receive_payment";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Refer to Customer -> Receive Payment";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    // /* -- TRANSACTION => CREATE SALES RECEIPT */
    // $code = "transaction-sales-receipt";
    // $nav[$page]['submenu'][$code]['label'] = "Create Sales Receipt";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . "customer" . "/sales_receipt";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Refer to Customer -> Sales Receipt";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    // /* -- TRANSACTION => CREDIT MEMO */
    // $code = "transaction-credit-memo-list";
    // $nav[$page]['submenu'][$code]['label'] = "Credit Memo";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . "customer" . "/credit_memo/list";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Refer to Customer -> Credit Memo";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    // $code = "transaction-product-repurchase";
    // $nav[$page]['submenu'][$code]['label'] = "Product Repurchase";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = "/member/customer/product_repurchase";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Layout (0%)";
    // $nav[$page]['submenu'][$code]['developer'] = "Erwin Guevara";

    /* E-COMMERCE */
    $page = "cashier";  
    $nav[$page]['name'] = "Cashier";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "calculator";
    $nav[$page]['type']     = "menu";

    /* -- E-COMMERCE => PRODUCT ORDERS */
    $code = "cashier-pos";
    $nav[$page]['submenu'][$code]['label'] = "Point of Sale";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/pos";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";  

    /* -- E-COMMERCE => PRODUCT ORDERS */
    $code = "cashier-issue-stocks";
    $nav[$page]['submenu'][$code]['label'] = "Issue Stocks";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/issue_stocks";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";  

    /* -- E-COMMERCE => PRODUCT ORDERS */
    $code = "cashier-transactions";
    $nav[$page]['submenu'][$code]['label'] = "Transactions";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/transactions";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";  
    
    /* -- E-COMMERCE => PRODUCT ORDERS */
    $code = "cashier-all-transactions";
    $nav[$page]['submenu'][$code]['label'] = "Transactions List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/transactions_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";  

    /* -- TAYLORMADE => COMMISSION CALC */
    $code = "cashier-commission-calculator";
    $nav[$page]['submenu'][$code]['label'] = "Commission Calculator";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/commission_calculator";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen Gutierrez</span>";  

    /* -- TAYLORMADE => SALES AGENT */
    $code = "cashier-sales-agent";
    $nav[$page]['submenu'][$code]['label'] = "Sales Agent";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/sales_agent";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen Gutierrez</span>";  


    /* E-COMMERCE */
    $page = "ecommerce";  
    $nav[$page]['name'] = "E-Commerce";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "shopping-cart";
    $nav[$page]['type']     = "menu";
    
    /* -- E-COMMERCE => PRODUCT ORDERS */
    $code = "ecommerce-product-order";
    $nav[$page]['submenu'][$code]['label'] = "Product Orders";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/product_order";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Erwin Guevara</span>";    

    /* -- E-COMMERCE => PRODUCT ORDERS */
    $code = "ecommerce-product-order2";
    $nav[$page]['submenu'][$code]['label'] = "Product Orders V2";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/product_order2";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "90% Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";    


    /* -- E-COMMERCE => WEBSITE ORDERS */
    // $code = "ecommerce-order-list";
    // $nav[$page]['submenu'][$code]['label'] = "Website Orders";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/order";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "90% Working";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Jimar Zape</span>";

    /* -- PRODUCTS => PRODUCT LIST */
    $code = "product-list";
    $nav[$page]['submenu'][$code]['label'] = "Product List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/product/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- PRODUCTS => PRODUCT COLLECTION */
    $code = "product-collection";
    $nav[$page]['submenu'][$code]['label'] = "Collection";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/product/collection/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- PRODUCTS => PRODUCT CODE */
    $code = "product-wishlist";
    $nav[$page]['submenu'][$code]['label'] = "Wishlist";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/wishlist/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Edward Guevarra</span>";

    /* -- PRODUCTS => PRODUCT CODE */
    $code = "product-coupon";
    $nav[$page]['submenu'][$code]['label'] = "Coupon Code";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/coupon/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- PRODUCTS => PRODUCT SHIPMENT STATUS */
    $code = "product-trackings";
    $nav[$page]['submenu'][$code]['label'] = "Trackings";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/trackings";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";


    
    /* -- E-COMMERCE => SHIPPING */
    // $code = "ecommerce-shipping";
    // $nav[$page]['submenu'][$code]['label'] = "Shipping";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/shipping";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Working - Need Revision - Add Product Dimension";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Jimar Zape</span>";
    
    /* -- E-COMMERCE => SETTING */
    // $code = "ecommerce-settings";
    // $nav[$page]['submenu'][$code]['label'] = "Payment Settings";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/settings";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Working 90%";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Jimar Zape</span>";
    
    /* MLM */
    $page = "mlm";  
    $nav[$page]['name'] = "Loyalty Reward System";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "braille";
    $nav[$page]['type']     = "menu";    


    /* -- MLM => SLOTS */
    $code = "mlm-slots";
    $nav[$page]['submenu'][$code]['label'] = "Reward Slots";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/slot";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Everyone</span>";
    
    $code = "mlm-developer";
    $nav[$page]['submenu'][$code]['label'] = "Reward Slots V2";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/developer";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout (0%)";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: blue'>Guillermo Tabligan</span>";

    $code = "mlm-stairstep-compute";
    $nav[$page]['submenu'][$code]['label'] = "Distribute Stairstep";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/stairstep/distribution";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout (0%)";
    $nav[$page]['submenu'][$code]['developer'] = "Erwin Guevarra";    

    $code = "mlm-rank-compute";
    $nav[$page]['submenu'][$code]['label'] = "Rank Update";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/rank/update";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout (0%)";
    $nav[$page]['submenu'][$code]['developer'] = "Erwin Guevarra";

    // $code = "mlm-product-repurchase-points";
    // $nav[$page]['submenu'][$code]['label'] = "Product Repurchase";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/product/repurchase/points";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Layout (0%)";
    // $nav[$page]['submenu'][$code]['developer'] = "Erwin Guevara";
  

    /* REWARD CONFIG */
    $segment = "submenu-mlm-transaction";
    $nav[$page]['submenu'][$segment]['name']   = "Transaction";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";


        /* -- MLM => MEMBERSHIP CODES */
        $code = "mlm-membership-claim-voucher";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Claim Voucher";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/claim_voucher";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'claim'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout Only";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: blue'>Everyone</span>";

        $code = "mlm-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Luke Glenn Jordan";

        $code = "mlm-wallet-encashment";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Payout Releasing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/encashment";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "mlm-wallet-payout";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Payout Releasing V2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/payout";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "mlm-wallet-distribute-cashback-points";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Distribute Cashback Points";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/distribute_cashback";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "mlm-wallet-gc-maintenance";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "GC Maintenance";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/gcmaintenance";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Guillermo Tabligan";

   

        $code = "mlm-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "mlm-slot-card";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Loyalty Cards";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/card";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "mlm-wallet-refill";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Wallet Adjustment";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/wallet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'wallet_refill', 'wallet_adjust', 'wallet_transfer'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";


        $code = "mlm-cutoff-distribution";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Cutoff Distribution";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/cutoff_distribution";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

        $code = "mlm-item-redeemable";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Item Redeemable Points";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/item_redeemable_points";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

    /* REWARD CONFIG */
    $segment = "submenu-mlm-codes";
    $nav[$page]['submenu'][$segment]['name']   = "Reward Codes";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";


        /* -- MLM => MEMBERSHIP CODES */
        $code = "mlm-membership-codes";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Membership Codes";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/code";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'view_receipt', 'sell_codes'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout Only";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: green'>Everyone</span>";

        $code = "mlm-product-code";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Product Code";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/product_code";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'product_code_reciept', 'product_code_sell_codes', 'see_all_invoice'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Erwin Guevara";

        /* -- MLM => MEMBERSHIP CODES */
        $code = "mlm-membership-codes-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Membership Codes V2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/code2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'view_receipt', 'sell_codes'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout Only";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: green'>Everyone</span>";

        $code = "mlm-product-code-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Product Code V2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/product_code2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'product_code_reciept', 'product_code_sell_codes', 'see_all_invoice'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: green'>Everyone</span>";



    /* REWARD CONFIG */
    $segment = "submenu-reward-configuration";
    $nav[$page]['submenu'][$segment]['name']   = "Reward Configuration";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        /* -- MLM => MEMBERSHIP  */
        $code = "mlm-membership";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Membership";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/membership";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','create_new_membership','edit_membership', 'delete_membership'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Working 100% - Not Tested Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Everyone";


        /* -- MLM => MARKETING PLAN */
        $code = "mlm-plan";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Marketing Plan";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/plan";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'configure_plan'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (50%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: green'>Everyone</span>";

        $code = "mlm-complan-setup";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Other Settings";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/complan_setup";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: blue'>Everyone</span>";
    
        $code = "mlm-product-points";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Product Points";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/product";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: blue'>Everyone</span>"; 

        $code = "mlm-product-discount";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Product Discount";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/product/discount";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout (0%)";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";


        $code = "mlm-point-log-complan";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Points Log Settings";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/point_log_complan";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Still Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Patrick Manarang";

        $code = "mlm-recaptcha";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Recaptcha";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/recaptcha";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Still Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Patrick Manarang";


    // $code = "mlm-recaptcha";
    // $nav[$page]['submenu'][$code]['label'] = "Recaptcha";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/recaptcha";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "still developing";
    // $nav[$page]['submenu'][$code]['developer'] = "Patrick Manarang";


    /* ACCOUNTING*/
    $page = "accounting"; 
    $nav[$page]['name'] = "Accounting";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "money";
    $nav[$page]['type']     = "menu";
   
    /* -- ACCOUNTING => SALES */
    // $code = "accounting-sales";
    // $nav[$page]['submenu'][$code]['label'] = "Sales";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/chart_of_account";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
   
    /* -- ACCOUNTING => EXPENSES */
    // $code = "accounting-expenses";
    // $nav[$page]['submenu'][$code]['label'] = "Expenses";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/chart_of_account";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";   

    /* -- ACCOUNTING => JOURNAL ENTRY */
    $code = "accounting-journal";
    $nav[$page]['submenu'][$code]['label'] = "Journal Entry";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/journal/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>"; 
    
    /* -- ACCOUNTING => CHART OF ACCOUNTS */
    $code = "accounting-settings";
    $nav[$page]['submenu'][$code]['label'] = "Chart of Accounts";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/chart_of_account";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Working 100% - Not Tested Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

        /* -- ACCOUNTING => SETTINGS */
    // $code = "accounting-chart-of-accounts";
    // $nav[$page]['submenu'][$code]['label'] = "Settings";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/settings";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yett";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- ACCOUNTING => IMPORT */
    $code = "accounting-import-list";
    $nav[$page]['submenu'][$code]['label'] = "Import";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/import/coa";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Working";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* PAYROLL */
    $page = "payroll";  
    $nav[$page]['name'] = "Payroll";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "calculator";
    $nav[$page]['type']     = "menu";

    /* -- EMPLOYEE=> LIST  */
    $code = "company-list";
    $nav[$page]['submenu'][$code]['label'] = "Company List";

    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/company_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";

    /* -- EMPLOYEE POSITION=> LIST  */
    $code = "employee-list";
    $nav[$page]['submenu'][$code]['label'] = "Employee List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/employee_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";



    /* PAYROLL TIME KEEPING */
    $code = "payroll-timekeeping";
    $nav[$page]['submenu'][$code]['label'] = "Time Keeping";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/time_keeping";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','salary_rates','summary_details','salary_detail'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";


    /* PAYROLL PROCESS */
    $code = "payroll-process";
    $nav[$page]['submenu'][$code]['label'] = "Payroll Process";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_process_module";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','processed','registered','posted','approved'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";



    // /* PAYROLL SUMMARY */
    // $code = "payroll-summary";
    // $nav[$page]['submenu'][$code]['label'] = "Payroll Summary";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_summary";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    



    // /* PAYROLL SUMMARY */
    // $code = "payroll-summary";
    // $nav[$page]['submenu'][$code]['label'] = "Payroll Summary";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_summary";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- EMPLOYEE=> LIST  */
    $code = "company-list";
    $nav[$page]['submenu'][$code]['label'] = "Company List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/company_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";

    /* -- EMPLOYEE POSITION=> LIST  */
    $code = "employee-list";
    $nav[$page]['submenu'][$code]['label'] = "Employee List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/employee_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";

    /* -- PAYROLL PERIOD => LIST  */
    // $code = "payroll-period";
    // $nav[$page]['submenu'][$code]['label'] = "Payroll Period";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_period_list";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";

    /* -- PAYROLL PERIOD => LIST  */
    // $code = "payroll-shift-group";
    // $nav[$page]['submenu'][$code]['label'] = "Shifting Group";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/shift_group";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";

    /* PAYROLL CONFIGURATION [SETTTINGS] */
    $code = "payroll-configuration";
    $nav[$page]['submenu'][$code]['label'] = "Payroll Configuration";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_configuration";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','Branch_Location','Department','Job_Title','Holiday','Holiday v2','Holiday_Default','Holiday Employee','Allowances','Allowances V2','Deductions','Deductions V2','Leave','Leave V2','Payroll_Group', 'Shift_Template','Journal_Tags','Payslip','Tax_Period','Tax_Table','SSS_Table','Philhealth_Table','Pagibig/HDMF'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";
    
    /* -- EMPLOYEE TIMESHEET=> LIST  */
    // $code = "employee-timesheet";
    // $nav[$page]['submenu'][$code]['label']         = "Employee Timesheet";
    // $nav[$page]['submenu'][$code]['code']          = $code;
    // $nav[$page]['submenu'][$code]['url']           = $path . $page . "/employee_timesheet";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status']        = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer']     = "Guillermo Tabligan";

    /* PAYROLL CALENDAR LEAVE */
    $code = "payroll-calendar-leave";
    $nav[$page]['submenu'][$code]['label'] = "Leave Scheduling";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/leave_schedule";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";

    // /* PAYROLL REGISTER */
    // $code = "payroll-register";
    // $nav[$page]['submenu'][$code]['label'] = "Payroll Registered";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_register";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";

    // /* PAYROLL POST */
    // $code = "payroll-post";
    // $nav[$page]['submenu'][$code]['label'] = "Payroll Posted";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_post";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";


    // /* PAYROLL APPROVED */
    // $code = "payroll-approved";
    // $nav[$page]['submenu'][$code]['label'] = "Payroll Approved";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_approved_view";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "Jimar Zape";


    /* PAYROLL REPORTS */
    $segment = "submenu-payroll-entries";
    $nav[$page]['submenu'][$segment]['name']   = "Payroll Entries";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

         /* -- ACCOUNTING => JOURNAL ENTRY */
        $code = "submenu-payroll-entries-journal-entries";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Journal Entry";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . "accounting/journal/list";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "<span style='color: green'>Bryan Kier Aradanas</span>";

        /* -- ACCOUNTING => CHART OF ACCOUNTS */
        $code = "submenu-payroll-entries-chart-of-account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Chart of Account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . "accounting/chart_of_account";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "<span style='color: green'>Bryan Kier Aradanas</span>";


    /* PAYROLL REPORTS */
    $segment = "submenu-payroll-reports";
    $nav[$page]['submenu'][$segment]['name']   = "Payroll Reports";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        /* -- REPORT => PRODUCTS  */
        $code = "payroll-report-goverment-contributions";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Government Forms";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/government_forms";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Everyone";


        /* -- REPORT => PRODUCTS  */
        $code = "payroll-report-bir-forms";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "BIR Forms";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/bir_forms";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "John Kenneth de Lara";


         /* -- REPORT => PRODUCTS  */
        $code = "payroll-report-loan-summary";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Loan Summary";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/loan_summary";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Kimbriel Oraya";

         /* -- REPORT => PRODUCTS  */
        $code = "payroll-register-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Payroll Register Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/payroll_register_report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Kimbriel Oraya";


        /* -- REPORT => PRODUCTS  */
        $code = "payroll-ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Payroll Ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/payroll_ledger";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Kimbriel Oraya";

        /* -- REPORT => PRODUCTS  */
        $code = "Branch-Tagging-Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Branch Tagging Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/branch_tagging_report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Everyone";
        
        /* -- REPORT => PRODUCTS  */
        $code = "13th-Month-Pay";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "13th Month Pay";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/13th_month_pay";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Kimbriel Oraya";
        
         /* -- REPORT => PRODUCTS  */
        $code = "leave-reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Leave Reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/leavev2_reports";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "John Kenneth de Lara";

        $code = "manpower-report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Man Power Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/manpower_report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "John Kenneth de Lara";

        /* -- REPORT => PRODUCTS  */
        $code = "Employee-Summary-Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Employee Summary Report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . $page . "/reports/employee_summary_report";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "Everyone";

    $segment = "admin-dashboard";
    $nav[$page]['submenu'][$segment]['name']   = "Admin Dashboard";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        /* -- REPORT => PRODUCTS  */
        $code = "employee-approver";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']         = "Employee Approver";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']          = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']           = $path . $page . "/payroll_admin_dashboard/employee_approver";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

        $code = "group-approver";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']         = "Group Approver";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']          = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']           = $path . $page . "/payroll_admin_dashboard/group_approver";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";


        $code = "access-level";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label']         = "Access level";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code']          = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url']           = $path . $page . "/payroll_admin_dashboard/access_level";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status']        = "Developing";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']     = "Everyone";

    /* PAYROLL Biometric */
    $code = "payroll-biometric";
    $nav[$page]['submenu'][$code]['label'] = "Payroll Biometric";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payroll_biometric";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Kimbriel Oraya";


    /* PAYROLL API */
    $code = "payroll-api";
    $nav[$page]['submenu'][$code]['label']          = "Payroll API";
    $nav[$page]['submenu'][$code]['code']           = $code;
    $nav[$page]['submenu'][$code]['url']            = $path . $page . "/payroll_api";
    $nav[$page]['submenu'][$code]['user_settings']  = ['access_page'];
    $nav[$page]['submenu'][$code]['status']         = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer']      = "Gullermo Tabligan";

    /* PROJECT */
    $page = "project";  
    $nav[$page]['name'] = "Project Management";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "heart";
    $nav[$page]['type']     = "menu";

    /* -- PROJECT => LIST  */
    $code = "project-list";
    $nav[$page]['submenu'][$code]['label'] = "Project List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/project_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Guillermo Tabligan";

    /* PRODUCT */
    // $page = "product";  
    // $nav[$page]['name'] = "Products & Services";
    // $nav[$page]['segment'] = $page;
    // $nav[$page]['icon'] = "list-alt";

    /* -- PRODUCTS => INVENTORY */
    // $code = "product-inventory";
    // $nav[$page]['submenu'][$code]['label'] = "Product Inventory";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/inventory";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Working 100% - Not Tested Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";
   
    /* -- PRODUCTS => SERVICE LIST */
    // $code = "product-service";
    // $nav[$page]['submenu'][$code]['label'] = "Service List";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/service_list";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
   
    /* -- PRODUCTS => INVENTORY */
    // $code = "product-collection";
    // $nav[$page]['submenu'][$code]['label'] = "Collections";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/collection";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    /* -- PRODUCTS => INVENTORY */
    // $code = "product-bundle";
    // $nav[$page]['submenu'][$code]['label'] = "Bundles";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/collection";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    /* ITEM  ERWIN */
    $page = "item";  
    $nav[$page]['name'] = "Items";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "cart-plus";
    $nav[$page]['type']     = "menu";
    
    /* -- ITEM => ITEM LIST  */
    // $code = "item-list";
    // $nav[$page]['submenu'][$code]['label'] = "Item List";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','add_auto_approve', 'can_approve_item_request', 'can_edit_other_item'];
    // $nav[$page]['submenu'][$code]['status'] = "100% DONE";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Erwin</span>";

    $code = "item-list-v2";
    $nav[$page]['submenu'][$code]['label'] = "Item List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/v2";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit','edit_bulk_item','refill-item','add_auto_approve', 'can_approve_item_request', 'can_edit_other_item'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";

    $code = "item-price-level";
    $nav[$page]['submenu'][$code]['label'] = "Item Price Level";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/price_level";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";

    $code = "item-redeemable";
    $nav[$page]['submenu'][$code]['label'] = "Redeemable Item";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/redeemable";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Patrick Manarang</span>";


    /* -- ITEM => ITEM CATEGORIES  */
    $code = "item-categories";
    $nav[$page]['submenu'][$code]['label'] = "Category";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/category";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";


    /* -- ITEM => UNIT OF MEASUREMENTS  */
    if($pis != 0)
    { 
        $code = "item-pis-unit-measurement";
        $nav[$page]['submenu'][$code]['label'] = "Unit of Measurements (P.I.S)";
        $nav[$page]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$code]['url'] = $path . $page . "/pis_unit_of_measurement";
        $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
        $nav[$page]['submenu'][$code]['status'] = "Still developing";
        $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    }

     /* -- ITEM => UNIT OF MEASUREMENTS  */
    $code = "item-unit-measurement";
    $nav[$page]['submenu'][$code]['label'] = "Unit of Measurements";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/unit_of_measurement";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

      /* -- ITEM => UNIT OF MEASUREMENTS TYPES */
    $code = "item-um-type";
    $nav[$page]['submenu'][$code]['label'] = "U/M Types";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/um_type";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- ITEM => WAREHOUSE  */
    $code = "item-warehouse";
    $nav[$page]['submenu'][$code]['label'] = "Warehouse";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/warehouse";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit','transfer','refill','adjust','add-serial','archived','restore','merchantwarehouse'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";


    /* -- ITEM => WAREHOUSE V2  */
    // $code = "item-warehouse-v2";
    // $nav[$page]['submenu'][$code]['label'] = "Warehouse V2";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/v2/warehouse";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit','transfer','refill','adjust','add-serial','archived','restore','merchantwarehouse'];
    // $nav[$page]['submenu'][$code]['status'] = "Still developing";
    // $nav[$page]['submenu'][$code]['developer'] = "Arcylen Garcia Gutierrez";

        $segment = "submenu-warehouse-v2";
        $nav[$page]['submenu'][$segment]['name']   = "Warehouse V2";
        $nav[$page]['submenu'][$segment]['segment']= $segment;
        $nav[$page]['submenu'][$segment]['type']   = "submenu";

             /* -- WAREHOUSE - INVENTORY */
            $code = "warehouse-inventory";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Warehouse Inventory";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/v2/warehouse";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','refill'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Developing";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "Everyone";

             /* -- WAREHOUSE - WIS */
            $code = "warehouse-issuance-slip";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Warehouse Transfer";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction/warehouse_transfer";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','issue','confirm'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

             /* -- WAREHOUSE - WIS */
            $code = "warehouse-receiving-report";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Receiving Report";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction/receiving_report";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','received'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

             /* -- WAREHOUSE - INVENTORY ADJUSTMENT */
            $code = "warehouse-inventory-adjustment";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Inventory Adjustment";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/warehouse/inventory_adjustment";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','adjust'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";


    /* -- ITEM => INVENTORY LOGS  */
    $code = "item-inventory-log";
    $nav[$page]['submenu'][$code]['label'] = "Inventory Logs";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/inventory_log";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- ITEM => WAREHOUSE  */
    $code = "item-manufacturer";
    $nav[$page]['submenu'][$code]['label'] = "Manufacturer";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/manufacturer";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- ITEM => IMPORT  */
    $code = "item-import";
    $nav[$page]['submenu'][$code]['label'] = "Import";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/import/item";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- ITEM => IMPORT  */
    $code = "item-adjustment-import";
    $nav[$page]['submenu'][$code]['label'] = "Import Inventory Adjustment";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/import/inventory-adjustment";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen Garcia Gutierrez</span>";

    /* -- ITEM => IMPORT  */
    $code = "item-import-bundle";
    $nav[$page]['submenu'][$code]['label'] = "Import Bundle";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/import/item-bundle";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Still developing";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen</span>";
    
    /* VENDORS */
    $page = "vendor";  
    $nav[$page]['name'] = "Vendors";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "user-secret";
    $nav[$page]['type']     = "menu";
    
    /* -- VENDORS => LIST  */
    $code = "vendor-list";
    $nav[$page]['submenu'][$code]['label'] = "Vendor List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- VENDORS => PURCHASE ORDER  */
    $code = "vendor-purchase-requisition";
    $nav[$page]['submenu'][$code]['label'] = "Purchase Requisition";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/purchase_requisition";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- VENDORS => PURCHASE ORDER  */
    $code = "vendor-purchase-order";
    $nav[$page]['submenu'][$code]['label'] = "Purchase Order <span class='blink-ctr ctr-span hidden po-count pull-right'></span>";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/purchase_order"; /* OLD /member/vendor/purchase_order/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    /* -- VENDORS => RECEIVE INVENTORY  */
    $code = "vendor-receive-inventory";
    $nav[$page]['submenu'][$code]['label'] = "Receive Inventory";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/receive_inventory";; /* OLD /member/vendor/receive_inventory/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    /* -- VENDORS => PAY BILLS  */
    $code = "vendor-bill";
    $nav[$page]['submenu'][$code]['label'] = "Enter Bills";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/enter_bills"; /* OLD /member/vendor/bill_list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- VENDORS => PAY BILLS  */
    $code = "vendor-pay-bills";
    $nav[$page]['submenu'][$code]['label'] = "Pay Bills <span class='blink-ctr ctr-span hidden bill-count pull-right'></span>";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/pay_bills"; /* OLD /member/vendor/paybill/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- VENDORS => CHECK  */
    $code = "vendor-check";
    $nav[$page]['submenu'][$code]['label'] = "Write Check";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/write_check";  /* OLD /member/vendor/write_check/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    /* -- VENDORS => BAD ORDERS  */
    $code = "vendor-debit-memo";
    $nav[$page]['submenu'][$code]['label'] = "Debit Memo";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/debit_memo"; /* OLD /member/vendor/debit_memo/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- VENDORS => IMPORT  */
    $code = "vendor-import-list";
    $nav[$page]['submenu'][$code]['label'] = "Import";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "vendors" . "/import/vendor";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

/*
    $segment = "vendor-transaction-v2";
    $nav[$page]['submenu'][$segment]['name']   = "Vendor Transaction V2";
    $nav[$page]['submenu'][$segment]['segment']= $segment;
    $nav[$page]['submenu'][$segment]['type']   = "submenu";

        $code = "vendor-purchase-request-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchase Requisition";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/purchase_requisition";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

        $code = "vendor-purchase-order-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Purchase Order";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/purchase_order";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

        $code = "vendor-receive-inventory-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Receive Inventory";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/receive_inventory";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

        $code = "vendor-enter-bills-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Enter Bills";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/enter_bills";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

        $code = "vendor-pay-bills-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Pay Bills";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/pay_bills";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

        $code = "vendor-write-check-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Write Check";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/write_check";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

        $code = "vendor-debit-memo-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Debit Memo";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/debit_memo";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet"; 

        $code = "vendor-bad-order-v2";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Bad Order";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
        $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "transaction". "/bad_order";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived'];
        $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
        $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";*/ 

    /* CUSTOMERS */
    $page = "customer";  
    $nav[$page]['name'] = "Customers";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "group";
    $nav[$page]['type']     = "menu";
    
    /* -- CUSTOMERS => CUSTOMER LIST  */
    $code = "customer-list";
    $nav[$page]['submenu'][$code]['label'] = "Customer List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit'];
    $nav[$page]['submenu'][$code]['status'] = "Working 90% - Need Search and Edit Customer Information";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Jimar Zape</span>";
    
    /* -- CUSTOMERS => CREATE ESTIMATE  */ 
    $code = "customer-estimate";
    $nav[$page]['submenu'][$code]['label'] = "Estimate & Quotation";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/estimate_quotation"; /*OLD /member/customer/estimate_list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen Gutierrez</span>";

    /* -- CUSTOMERS => CREATE SALES ORDER  */
    $code = "customer-sales-order";
    $nav[$page]['submenu'][$code]['label'] = "Sales Order";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/sales_order"; /*OLD /member/customer/sales_order_list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen Gutierrez</span>";

    /* -- CUSTOMERS => CUSTOMER INVOICE  */
    $code = "customer-invoice";
    $nav[$page]['submenu'][$code]['label'] = "Sales Invoice <span class='blink-ctr ctr-span hidden inv-count pull-right'></span>";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/sales_invoice"; /*OLD /member/customer/invoice_list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Done";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- CUSTOMERS => CUSTOMER Sales Receipt  */
    $code = "customer-sales-receipt";
    $nav[$page]['submenu'][$code]['label'] = "Sales Receipt";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/sales_receipt"; /*OLD /member/sales_receipt/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

     /* -- CUSTOMERS => WAREHOUSE ISSUANCE SLIP  */
    $code = "customer-warehouse-issuance-slip";
    $nav[$page]['submenu'][$code]['label'] = "Warehouse Issuance Slip";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/wis";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";

     /* -- CUSTOMERS => WAREHOUSE ISSUANCE SLIP  */
     /* 
    $code = "customer-warehouse-issuance-slip";
    $nav[$page]['submenu'][$code]['label'] = "Delivery Report";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/delivery_report";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone"; */

    /* -- CUSTOMERS => CUSTOMER RECEIVE PAYMENT  */
    $code = "customer-receive-payment";
    $nav[$page]['submenu'][$code]['label'] = "Receive Payment";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/receive_payment"; /*OLD /member/receive_payment/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Done";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";    

    /* -- CUSTOMERS => CREDIT MEMO */
    $code = "customer-credit-memo-list";
    $nav[$page]['submenu'][$code]['label'] = "Credit Memo";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/credit_memo"; /*OLD /member/credit_memo/list */
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Refer to Customer -> Credit Memo";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- CUSTOMERS => IMPORT */
    $code = "customer-import-list";
    $nav[$page]['submenu'][$code]['label'] = "Import";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "customer" . "/import/customer";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

      /*  $segment = "customer-accounting-transaction";
        $nav[$page]['submenu'][$segment]['name']   = "Customer Transaction v2";
        $nav[$page]['submenu'][$segment]['segment']= $segment;
        $nav[$page]['submenu'][$segment]['type']   = "submenu";

            $code = "customer-estimate-v2";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Estimate and Quotation";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction" . "/estimate_quotation";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

            $code = "customer-sales-order-v2";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Order";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction" . "/sales_order";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

            $code = "customer-invoice-v2";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Invoice";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction" . "/sales_invoice";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";


            $code = "customer-sales-receipt-v2";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Receipt";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction" . "/sales_receipt";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";


            $code = "customer-receive-payment-v2";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Receive Payment";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction" . "/receive_payment";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";


            $code = "customer-credit-memo-v2";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Credit Memo";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."transaction" . "/credit_memo";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";


            $code = "customer-wis-v2";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Warehouse Issuance Slip";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = "/member/customer/wis/create"; //$path ."transaction" . "/wis"
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet"; */



    $page = "merchant";  
    $nav[$page]['name'] = "Merchant";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "user-secret";
    $nav[$page]['type']     = "menu";

    $code = "merchant-markup-per-item";
    $nav[$page]['submenu'][$code]['label'] = "Product Markup";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "merchant" . "/markup";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: blue'>Everyone</span>";

    $code = "merchant-report";
    $nav[$page]['submenu'][$code]['label'] = "Report";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "merchant" . "/report";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: blue'>Everyone</span>";

    $code = "merchant-commission";
    $nav[$page]['submenu'][$code]['label'] = "Commission";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "merchant" . "/commission";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: blue'>Everyone</span>";

    $code = "merchant-commission-report";
    $nav[$page]['submenu'][$code]['label'] = "Commission Report";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "merchant" . "/commission-report";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "on progress";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: blue'>Patrick Manarang</span>";

    $code = "merchant-ewallet";
    $nav[$page]['submenu'][$code]['label'] = "E-Wallet";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "merchant" . "/ewallet";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: blue'>Everyone</span>";

   

    /* VENDORS */
    $page = "vendor";  
    $nav[$page]['name'] = "Vendors";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "user-secret";
    $nav[$page]['type']     = "menu";
    
        /* PURCHASING INVENTORY SYSTEM */
    $page = "pis";
    $nav[$page]['name']     = "PIS";
    $nav[$page]['segment']  = $page;
    $nav[$page]['icon']     = "truck";
    $nav[$page]['type']     = "menu";

        $segment = "submenu-sales-delivery";
        $nav[$page]['submenu'][$segment]['name']   = "Sales & Delivery";
        $nav[$page]['submenu'][$segment]['segment']= $segment;
        $nav[$page]['submenu'][$segment]['type']   = "submenu";

             /* -- PIS => SIR */
            $code = "pis-lof";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Load Out Form <span class='blink-ctr ctr-span hidden lof-count pull-right'></span>";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/lof";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

             /* -- PIS ['submenu'][$segment]=> SIR */
            $code = "pis-sir";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Stock Issuance Report <span class='blink-ctr ctr-span hidden sir-count pull-right'></span>";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/sir";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','edit','add','archived','open-sir','sync'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

             /* -- PIS ['submenu'][$segment]=> ILR */
            $code = "pis-ilr";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Incoming Load Report <span class='blink-ctr ctr-span hidden ilr-count pull-right'></span>";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . $page . "/ilr";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','processed-ilr'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

    // /* -- PIS => Manual Invoicing */
    // $code = "pis-manual-invoicing";
    // $nav[$page]['submenu'][$code]['label'] = "Manual Invoicing";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page ."/manual_invoice";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

        $segment = "submenu-cashier";
        $nav[$page]['submenu'][$segment]['name']   = "Cashier";
        $nav[$page]['submenu'][$segment]['segment']= $segment;
        $nav[$page]['submenu'][$segment]['type']   = "submenu";

            $code = "agent-collectiong";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Collection <span class='blink-ctr ctr-span hidden col-count pull-right'></span>";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "cashier/collection";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No Progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

            $code = "pis-agent";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Agent List";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path . "cashier/agent_list";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page','add','edit','archived'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No progress Yet";  
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

            $code = "pis-sales-liquidation";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Sales Liquidation";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."cashier/sales_liquidation";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "No progress Yet";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- PIS => SIR */
    $code = "tablet-sir";
    $nav[$page]['submenu'][$code]['label'] = "Login Tablet";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = "/tablet";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','create-invoice'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /*Press Release Email System*/

    $page = "press_release_email";
    $nav[$page]['name']     = "Press Release";
    $nav[$page]['segment']  = $page;
    $nav[$page]['icon']     = "envelope";
    $nav[$page]['type']     = "menu";

        $segment = "page-create-press-release";
        $nav[$page]['submenu'][$segment]['name']   = "Create Press Release";
        $nav[$page]['submenu'][$segment]['segment']= $segment;
        $nav[$page]['submenu'][$segment]['type']   = "submenu";


            $code = "create-press-release";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label'] = "Create";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url'] = $path ."page/". $page . "/create_press_release";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page', 'activate_theme'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status'] = "Layout Only";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer'] = "<span style='color: green'>Ephraim Dognidon   </span>";

            $code = "submenu-list-emails-press-release-sample";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Draft Press Release";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . "page/" . $page . "/email_list_press_release";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "<span style='color: green'>Ephraim Dognidon   </span>";

            $code = "page-analytics-press-release";
            $nav[$page]['submenu'][$code]['label'] = "Analytics";
            $nav[$page]['submenu'][$code]['code'] = $code;
            $nav[$page]['submenu'][$code]['url'] = $path ."page/". $page . "/create_press_release";
            $nav[$page]['submenu'][$code]['user_settings'] = ['access_page', 'activate_theme'];
            $nav[$page]['submenu'][$code]['status'] = "Layout Only";
            $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Ephraim Dognidon   </span>";

        $segment = "page-view-press-release";
        $nav[$page]['submenu'][$segment]['name']   = "View Press Release";
        $nav[$page]['submenu'][$segment]['segment']= $segment;
        $nav[$page]['submenu'][$segment]['type']   = "submenu";

            $code = "submenu-list-email-sent-press-release-sample";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Archive Press Release";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path ."page/" . $page . "/email_sent_press_release";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "<span style='color: green'>Ephraim Dognidon   </span>";

            $code = "submenu-recipient-list-press-release-sample";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['label']       = "Recipient List";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['code']        = $code;
            $nav[$page]['submenu'][$segment]['submenu'][$code]['url']         = $path . "page/" . $page . "/recipient_list_press_release";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['user_settings'] = ['access_page'];
            $nav[$page]['submenu'][$segment]['submenu'][$code]['status']      = "Developing";
            $nav[$page]['submenu'][$segment]['submenu'][$code]['developer']   = "<span style='color: green'>Ephraim Dognidon   </span>";




    /* MANAGE CONTENT  */
    $page = "page"; 
    $nav[$page]['name'] = "Manage Pages";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "star";
    $nav[$page]['type']     = "menu";
    
    /* -- MANAGE CONTENT => PAGE INFORMATION */
    $code = "page-event";
    $nav[$page]['submenu'][$code]['label'] = "Events";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/events";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen Garcia Gutierrez </span>";

    /* -- MANAGE CONTENT => PAGE INFORMATION */
    $code = "page-theme";
    $nav[$page]['submenu'][$code]['label'] = "Themes";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/themes";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page', 'activate_theme'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";

    $code = "page-post";
    $nav[$page]['submenu'][$code]['label'] = "Posts";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/post";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add_post', 'edit_post', 'archive_post'];
    $nav[$page]['submenu'][$code]['status'] = "Working 100%";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Edward Guevarra</span>";
    
    $code = "page-partner";
    $nav[$page]['submenu'][$code]['label'] = "Partners";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/partnerview";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>James Omosora</span>";

    $code = "page-list";
    $nav[$page]['submenu'][$code]['label'] = "Page Information";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/content";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";

    /* REWARD CONFIG */
    
    // $code = "page-partnerview";
    // $nav[$page]['submenu'][$code]['label'] = "partnerview";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/partnerview";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>James Omosora</span>";

    // $code = "page-partnerviewedit";
    // $nav[$page]['submenu'][$code]['label'] = "partnerviewedit";
    // $nav[$page]['submenu'][$code]['code'] = $code;
    // $nav[$page]['submenu'][$code]['url'] = $path . $page . "/partnerviewedit";
    // $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    // $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    // $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>James Omosora</span>";
    
    /* -- MANAGE CONTENT => CONTACT INFORMATION */
    $code = "page-contact";
    $nav[$page]['submenu'][$code]['label'] = "Contact Information";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/contact";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- MANAGE CONTENT => STORE INFORMATION */
    $code = "page-store-information";
    $nav[$page]['submenu'][$code]['label'] = "Store Information";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/store_information";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* UTILITIES  */
    $page = "utilities"; 
    $nav[$page]['name'] = "Utilities";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "gear";
    $nav[$page]['type']     = "menu";

    /* -- UTILITIES => AdminN ACCOUNTS */
    $code = "utilities-admin-accounts";
    $nav[$page]['submenu'][$code]['label'] = "Admin Accounts";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/admin-list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    /* -- UTILITIES => ADMIN POSITIONS */
    $code = "utilities-admin-positions";
    $nav[$page]['submenu'][$code]['label'] = "Admin Positions";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/position";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add/edit'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";
    
    /* -- UTILITIES => AUDIT TRAIL */
    $code = "utilities-audit";
    $nav[$page]['submenu'][$code]['label'] = "Audit Trail";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/audit";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- UTILITIES => AUDIT TRAIL */
    $code = "utilities-printable";
    $nav[$page]['submenu'][$code]['label'] = "Printable Settings";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "transaction/printable_header";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Arcylen Gutierrez";

    /* -- UTILITIES => AUDIT TRAIL */
    $code = "utilities-signatories";
    $nav[$page]['submenu'][$code]['label'] = "Signatories Setting";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "settings/signatories/all";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "Arcylen Gutierrez";
    
    /* -- UTILITIES =>  */
    $code = "utilities-client-list";
    $nav[$page]['submenu'][$code]['label'] = "Owner's Information Settings";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/client_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','update_password'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- UTILITIES =>  */
    $code = "utilities-inventory-reset";
    $nav[$page]['submenu'][$code]['label'] = "Reset Inventory";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path  . "transaction/reset";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','update_password'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* Maintenance */
    $page = "maintenance";  
    $nav[$page]['name'] = "Maintenance";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "trello";
    $nav[$page]['type']     = "menu";

    $code = "maintenance-item-range";
    $nav[$page]['submenu'][$code]['label'] = "Item Range Sales Discount";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "utilities/item_range_sales_discount";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit','archived'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    $code = "maintenance-sales-rep";
    $nav[$page]['submenu'][$code]['label'] = "Sales Representatives";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "utilities/sales-representative";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit','archived'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- MAINTENACE => landing-cost */
    $code = "maintenance-landing-cost";
    $nav[$page]['submenu'][$code]['label'] = "Landing Cost"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/landing_cost";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- MAINTENACE => Payment Method */
    $code = "maintenance-payment-method";
    $nav[$page]['submenu'][$code]['label'] = "Payment Method"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payment_method";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- MAINTENACE => Payment Method */
    $code = "maintenance-online-payment-method";
    $nav[$page]['submenu'][$code]['label'] = "Online Payment Method"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/online_payment";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    $code = "maintenance-email-content";
    $nav[$page]['submenu'][$code]['label'] = "Email Content"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/email_content";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    $code = "maintenance-email-header-footer";
    $nav[$page]['submenu'][$code]['label'] = "Email Header & Footer"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/email_header_footer";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

    /* -- MAINTENACE => Social App Keys */
    $code = "maintenance-social-app-keys";
    $nav[$page]['submenu'][$code]['label'] = "Social Networking App Keys"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/app_keys";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Arcylen Gutierrez</span>";

    /* -- MAINTENACE => Sms Settings */
    $code = "maintenance-sms-content";
    $nav[$page]['submenu'][$code]['label'] = "SMS"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/sms";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    $code = "maintenance-terms";
    $nav[$page]['submenu'][$code]['label'] = "Terms"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/terms/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    $code = "maintenance-location";
    $nav[$page]['submenu'][$code]['label'] = "Location"; 
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/location/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- EMPLOYEE POSITION=> LIST  */
    $code = "pis-agent-position";
    $nav[$page]['submenu'][$code]['label'] = "Agent Position";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "utilities/agent_position";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit','archived'];
    $nav[$page]['submenu'][$code]['status'] = "No progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";

     /* -- PIS => TRUCK */
    $code = "pis-truck";
    $nav[$page]['submenu'][$code]['label'] = "Truck List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . "utilities/truck_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page','add','edit','archived'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";


    /* DEVELOPERS  */
    $page = "developer"; 
    $nav[$page]['name'] = "Developers";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "diamond";
    $nav[$page]['type']     = "menu";

    /* -- DEVELOPERS => PAGE STATUS */
    $code = "developer-page-status";
    $nav[$page]['submenu'][$code]['label'] = "Page Status";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/status";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "<span style='color: green'>(DONE)</span> You are looking at this page now.";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Guillermo Tabligan</span>";   

    /* -- DEVELOPERS => DOCUMENTATION */
    $code = "developer-single-entry";
    $nav[$page]['submenu'][$code]['label'] = "Single Entry";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/single_entry";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";

    /* -- DEVELOPERS => DOCUMENTATION */
    $code = "developer-auto-entry-independent";
    $nav[$page]['submenu'][$code]['label'] = "Auto Entry (Independent)";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/auto_entry_independent";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";

     /* -- DEVELOPERS => REMATRIX */
    $code = "developer-rematrix";
    $nav[$page]['submenu'][$code]['label'] = "Rematrix";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/rematrix";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Erwin Guevarra";

     /* -- DEVELOPERS => DOCUMENTATION */
    $code = "developer-auto-entry";
    $nav[$page]['submenu'][$code]['label'] = "Auto Entry";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/auto_entry";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";   
    
    $code = "developer-simulate";
    $nav[$page]['submenu'][$code]['label'] = "Simulate";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/simulate";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Erwin Guevarra";

     /* -- DEVELOPERS => DOCUMENTATION */
    $code = "developer-documentation";
    $nav[$page]['submenu'][$code]['label'] = "Code Documentation";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/documentation";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";
    
    $code = "developer-payment-logs";
    $nav[$page]['submenu'][$code]['label'] = "Payment Logs";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/payment_logs";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";

    $code = "developer-reset-slot";
    $nav[$page]['submenu'][$code]['label'] = "Reset Slot";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/reset_slot";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Developing";
    $nav[$page]['submenu'][$code]['developer'] = "Everyone";
    
    return $nav;
}
//dd(page_list());
?>