<?php

function page_list()
{
    $path = '/member/';
    
/* PRODUCT */
    $page = "product";  
    $nav[$page]['name'] = "Products & Services";
    $nav[$page]['segment'] = $page;
    $nav[$page]['icon'] = "list-alt";
    
    /* -- PRODUCTS => PRODUCT LIST */
    $code = "product-list";
    $nav[$page]['submenu'][$code]['label'] = "Product List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Working 70% - Under Revision";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";

    /* -- PRODUCTS => INVENTORY */
    $code = "product-inventory";
    $nav[$page]['submenu'][$code]['label'] = "Product Inventory";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/inventory";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Working 100% - Not Tested Yet";
    $nav[$page]['submenu'][$code]['developer'] = "<span style='color: green'>Bryan Kier Aradanas</span>";
   
    /* -- PRODUCTS => SERVICE LIST */
    $code = "product-service";
    $nav[$page]['submenu'][$code]['label'] = "Service List";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/service_list";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
   
    /* -- PRODUCTS => INVENTORY */
    $code = "product-collection";
    $nav[$page]['submenu'][$code]['label'] = "Collections";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/collection";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "Layout Only";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    /* -- PRODUCTS => INVENTORY */
    $code = "product-bundle";
    $nav[$page]['submenu'][$code]['label'] = "Bundles";
    $nav[$page]['submenu'][$code]['code'] = $code;
    $nav[$page]['submenu'][$code]['url'] = $path . $page . "/collection";
    $nav[$page]['submenu'][$code]['user_settings'] = ['access_page'];
    $nav[$page]['submenu'][$code]['status'] = "No Progress Yet";
    $nav[$page]['submenu'][$code]['developer'] = "No Developer Yet";
    
    return $nav;
}
//dd(page_list());
?>