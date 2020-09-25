@extends("press_admin.admin")
@section("pressview")
<div class="background-container">
    <div class="pressview">
      <div class="dashboard-container">
        <div class="title-container">Analytics</div>
        <div class="table-view ">
          <table>
                <tr>
                  <th style="text-align: center;width: 20%">Date / Time </th>
                  <th style="text-align: center;width: 20%">Title / Subject</th>
                  <th style="text-align: center;width: 20%">Sender</th>
                  <th style="text-align: center;width: 20%">Action</th>
                </tr>
                @foreach($analytics_view as $view)
                <tr>
                  <td>{{date("m-d-Y\ / h:i:s a",($view->ts))}} </td>
                  <td>{{$view-> subject}}</td>
                  <td>{{$view-> sender}}</td>
                  <td>
                      <span class="create-button" ><a href="/pressadmin/dashboard/view?subject={{ Crypt::encrypt($view->subject)}}">VIEW</a>
                  </td>
                </tr>
                @endforeach
          </table>
        </div>
      </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_admin_dashboard.css">
@endsection

@section("script")

@endsection
