
==================== CONTROLLER ======================
use App\Globals\Vendor;

$data["_vendor"] = Vendor::getAllVendor();

==================== BLADE ===========================

<select class="drop-down-vendor">
	@ include("member.load_ajax_data.load_vendor")
</select>

==================== JAVASCRIPT ======================

$(".drop-down-vendor").globalDropList(
{
    link: '/member/vendor/add',
    link_size: 'lg',
    placeholder: 'Vendor'
});