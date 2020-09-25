/* Transaction Journal Entry */

$entry["entry_date"] = "2017-01-02";
$entry["je_id"]		 = 2;

$entry_data[0]["account_id"] 	= 4;
$entry_data[0]["type"] 			= Credit;
$entry_data[0]["entry_amount"] 	= 3000;
$entry_data[0]["name_id"] 		= 2;
$entry_data[0]["name_reference"] = Customer;

$entry_data[1]["account_id"] 	= 4;
$entry_data[1]["type"] 			= Debit;
$entry_data[1]["entry_amount"] 	= 3000;
$entry_data[1]["name_id"] 		= '';
$entry_data[1]["name_reference"] = '';

$remarks = "this is for the remarks";  

/* CALL METHOD TO PROCESS */
Accounting::postJournalEntry($entry, $entry_data, $remarks);