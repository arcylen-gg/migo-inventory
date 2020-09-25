<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Crypt;
use Redirect;
use View;
use DB;
use Input;
use File;
use Image;
use App\Models\Tbl_press_release_recipient;
use App\Tbl_pressiq_press_releases;


class ShopNewsRoomController extends Shop
{
    public function index()
    {
        $pr = DB::table('tbl_pressiq_press_releases')
                ->where('pr_status', "sent")
                ->orderByRaw('pr_date_sent DESC')
                ->paginate(5);

        $data["pr"]=$pr;
        $data["page"] = "News Room";
        return view("news_room", $data);
    }
    public function news_room_view($pid)
    {
        $pr = DB::table('tbl_pressiq_press_releases')
                ->where('pr_id', $pid)
                ->get();

        $data["pr"]=$pr;
        $data["page"] = "News Room";

        $pr = DB::table('tbl_pressiq_press_releases')
                ->where('pr_status', "sent")
                ->orderByRaw('pr_date_sent DESC')
                ->paginate(6);
        $data["page"] = "Press Release - View";
        $data["pr_newsroom"]=$pr;
        return view("news_room_view", $data);
    }

    public function newsroom_search(Request $request)
    {  
      $search_newsroom = $request->search_newsroom;
      $data["pr"] = Tbl_pressiq_press_releases::where('pr_headline','like','%'.$search_newsroom.'%')
                    ->get();

      return view("search_news_room", $data);
    }
}