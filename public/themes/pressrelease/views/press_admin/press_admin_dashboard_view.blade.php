@extends("press_admin.admin")
@section("pressview")
<div class="background-container">
    <div class="pressview">
      <div class="dashboard-container">
                <div class="title-container">Details
                   <div class="button-container pull-right">
                        <span class="create-button" ><a href="/pressadmin/dashboard">BACK</a>
                    </div>
                </div>
         
                   
               
        <div class="table-view ">
          <table>
                <tr>
                  <th style="text-align: center;width: 20%">Date / Time </th>
                  <th style="text-align: center;width: 20%">Title / Subject</th>
                  <th style="text-align: center;width: 10%">Status </th>
                  <th style="text-align: center;width: 15%">Sender </th>
                  <th style="text-align: center;width: 20%">Recipients</th>
                  <th style="text-align: center;width: 10%">No. Email Open</th>
                  <th style="text-align: center;width: 20%">Clicks</th>
                </tr>
                @foreach($analytics_view as $view)
                <tr>
                  <td>{{date("m-d-Y\ / h:i:s a",($view->ts))}} </td>
                  <td>{{$view-> subject}}</td>
                  <td>{{$view-> state}}</td>
                  <td>{{$view-> sender}}</td>
                  <td>{{$view-> email}}</td>
                  <td>{{$view-> opens}}</td>
                  <td>{{$view-> clicks}}</td>
                </tr>
                @endforeach
          </table>
        </div>
      </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_admin_dashboard_view.css">
@endsection

@section("script")

@endsection
