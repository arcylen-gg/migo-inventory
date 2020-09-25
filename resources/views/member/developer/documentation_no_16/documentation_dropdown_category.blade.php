
==================== CONTROLLER ======================
use App\Globals\Category;

$data['_category']      = Category::getAllCategory();

==================== BLADE ===========================

<select class="drop-down-category">
    @ include("member.load_ajax_data.load_category", ['add_search' => ""])
</select>

==================== JAVASCRIPT ======================

$(".drop-down-category").globalDropList(
{
    link: '/member/item/category/modal_create_category',
    link_size: 'md',
    placeholder: 'Category'
});