
==================== CONTROLLER ======================
use App\Globals\Accounting;

$data['_account'] = Accounting::getAllAccount();

==================== BLADE ===========================

<select class="drop-down-coa">
	@ include("member.load_ajax_data.load_chart_account", ['add_search' => ""])
</select>

==================== JAVASCRIPT ======================

$(".drop-down-coa").globalDropList(
{
    link: '/member/accounting/chart_of_account/popup/add',
    link_size: 'md',
    placeholder: 'Chart of Account'
});
