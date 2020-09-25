<?php
namespace App\Http\Controllers;

class LeadController extends Shop
{
    public function ref($slot_no)
    {
        $store["lead_sponsor"] = $slot_no;

        dd($slot_no);

        session($store);
        return redirect("/");
    }
}