
==================== CONTROLLER ======================
use App\Globals\Customer;

$data["_customer"] = Customer::getAllCustomer();

==================== BLADE ===========================

<select class="drop-down-customer">
	@ include("member.load_ajax_data.load_customer")
</select>

==================== JAVASCRIPT ======================

$(".drop-down-customer").globalDropList(
{
    link: '/member/customer/modalcreatecustomer',
    link_size: 'lg',
    placeholder: 'Customer'
});