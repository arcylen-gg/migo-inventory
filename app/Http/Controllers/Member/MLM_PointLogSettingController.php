<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Models\Tbl_mlm_point_log_setting;

class MLM_PointLogSettingController extends Member
{
    public function index()
    {
    	$data['page'] = "Point Log Settings";
    	return view("member.mlm_point_log_setting.mlm_point_log_setting",$data);
    }
    public function add()
    {
    	$data['page'] = "Add New Notification";
        $types = Tbl_mlm_point_log_setting::get();
        $existing_types = array();
        foreach($types as $type)
        {
            array_push($existing_types, $type->point_log_setting_type);
        }
        $data['types'] = $existing_types;
    	return view("member.mlm_point_log_setting.point_log_setting_add",$data);
    }
    public function submit_add(Request $request)
    {
    	$insert = $request->except('_token');
    	// $insert->unset('_token');
    	if(Tbl_mlm_point_log_setting::insert($insert))
		{
			$reponse['call_function'] = 'success';
		}
		else
		{
			$reponse['call_function']= 'error';
		}
		return json_encode($reponse);
    }
    public function table()
    {
    	$data['settings'] = Tbl_mlm_point_log_setting::get();
    	return view("member.mlm_point_log_setting.point_log_setting_table",$data);
    }
    public function modify()
    {
    	$data['page'] = "Modify";
    	$data['settings'] = Tbl_mlm_point_log_setting::where("point_log_setting_id",request('id'))->get();
    	$data['id'] = request('id');
    	return view("member.mlm_point_log_setting.point_log_setting_add",$data);
    }
    public function submit_modify(Request $request)
    {
    	$update['point_log_setting_plan_code'] = $request->point_log_setting_plan_code;
    	$update['point_log_setting_name'] = $request->point_log_setting_name;
    	$update['point_log_setting_type'] = $request->point_log_setting_type;
    	$update['point_log_notification'] = $request->point_log_notification;
    	if(Tbl_mlm_point_log_setting::where('point_log_setting_id',$request->point_log_setting_id)->update($update))
		{
			$response['call_function'] = 'success';
		}
		else
		{
			$response['call_function']= 'error';
		}
    	return json_encode($response);
    }
}
