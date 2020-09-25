/* Transaction Journal Entry */

$entry["reference_module"]  = "invoice";
$entry["reference_id"]      = 24;
$entry["name_id"]           = 0;
$entry["total"]             = 2000;
$entry["vatable"]           = 20.0;
$entry["discount"]          = 10;
$entry["ewt"]               = 25;

$entry_data[0]['item_id']            = 20;
$entry_data[0]['entry_qty']          = 2;
$entry_data[0]['vatable']            = 0;
$entry_data[0]['discount']           = 0;
$entry_data[0]['entry_amount']       = 600; // plus discount if available
$entry_data[0]['entry_description']  = "description";    

$entry_data[1]['item_id']            = 23;
$entry_data[1]['entry_qty']          = 5;
$entry_data[1]['vatable']            = 0;
$entry_data[1]['discount']           = 0;
$entry_data[1]['entry_amount']       = 250; // plus discount if available
$entry_data[1]['entry_description']  = "description";

$remarks = "this is for the remarks";  

/* CALL METHOD TO PROCESS */
Accounting::postJournalEntry($entry, $entry_data, $remarks);