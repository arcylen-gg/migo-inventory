var warehouse_reorder = new warehouse_reorder();
var global_tr_html = $(".div-script tbody").html();
var item_selected = ''; 
var bin_selected = ''; 
var load_table_data = {};

function warehouse_reorder()
{
	init();

	function init()
	{
		initialize_search_item();
		
	}
	function initialize_search_item()
	{
		$('.select-warehouse').globalDropList(
        {
        	hasPopup: 'false',
            width : "100%",
            maxHeight: "309px",
        });
	}
}
function success_warehouse_reorder(data)
{
	if(data.status == "success")
	{
        toastr.success(data.status_message);
	}
}