
==================== CONTROLLER ======================
use App\Globals\Item;

$data['_item']	= Item::get_all_category_item();

==================== BLADE ===========================

<select class="drop-down-item">
    @ include("member.load_ajax_data.load_item_category", ['add_search' => ""])
</select>

==================== JAVASCRIPT ======================

$(".drop-down-item").globalDropList(
{
    link: '/member/item/add',
    link_size: 'lg',
    maxHeight: "309px",
    placeholder: 'Item'
});
