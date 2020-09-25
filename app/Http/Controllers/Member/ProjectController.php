<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_project;
use App\Models\Tbl_project_type;

class ProjectController extends Member
{
    public function index()
    {
        $data["page"] = "Project List";
        return view("member.project.project", $data);
    }
    public function table()
    {
        $data["page"]           = "Project Table";
        $query                  = Tbl_project::joinType();

        if(request("activetab") == "active")
        {
            $query->where("archived", 0);
        }
        else
        {
            $query->where("archived", 1);
        }

        if(request("search") != "")
        {
            $query->where("project_name", "LIKE", "%" . request("search") . "%");
        }

        $data["_project"]       = $query->get();

        return view("member.project.project_table", $data);
    }
    public function add()
    {
        $data["page"]   = "Project Add";
        $data["_type"]  = Tbl_project_type::get();

        return view("member.project.project_add", $data);
    }
    public function submit_add()
    {
        $insert = request()->all();
        unset($insert["_token"]);
        Tbl_project::insert($insert);

        $response["status"] = "success";
        $response["call_function"] = "success_project_create";
        return json_encode($response);
    }
    public function archive()
    {
        $update["archived"] = 1;
        Tbl_project::where("project_id", request("project_id"))->update($update);
    }
    public function restore()
    {
        $update["archived"] = 0;
        Tbl_project::where("project_id", request("project_id"))->update($update);
    }
    public function modify()
    {
        $id = request("id");
        $data['project'] = Tbl_project::joinType()->where("project_id",$id)->first();
        $data["_type"]  = Tbl_project_type::get();
        return view("member.project.project_modify",$data);
    }
    public function submit_modify()
    {
        $update  = request()->all();
        $id = request("project_id");
        unset($update['_token']);
        unset($update['project_id']);
        if(Tbl_project::where("project_id",$id)->update($update))
        {
            $response['call_function'] = "success_project_create";   
        }
        return json_encode($response);
    }
    public function view()
    {
        return view("member.project.project_view");
    }
    public function addTask()
    {
        $data["page"]   = "Project Add";
        $data['project_name'] = "Sample Project";
        return view("member.project.project_task_add", $data);
    }

}