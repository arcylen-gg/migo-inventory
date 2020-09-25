@extends("press_user.member")
@section("pressview")
<style type="text/css">
  .table-view
  {
    overflow-y: scroll;
    height: 500px;
  }
</style>
<div class="background-container">
    <div class="pressview">
        <div class="dashboard-container">
            <!-- Dashboard -->
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="title-container">Campaign Details</div>
                </div>
                <div class="pull-right">
                    <div class="button-container">
                        <span class="create-button" ><a href="/pressuser/analytics">BACK</a>
                    </div>
                </div>
            </div>
            <div class="table-view">
              <table style="width:100%;">
                    <tr>
                      <th style="text-align: center;width: 20%">Date / Time </th>
                      <th style="text-align: center;width: 20%">Title / Subject</th>
                      <th style="text-align: center;width: 10%">Action </th>
                    </tr>
                    @foreach($analytics_view as $view)
                    <tr>
                      <td>{{date("m-d-Y\ / h:i:s a",($view->ts))}} </td>
                      <td>{{$view-> subject}}</td>
                      <td>
                        <span class="create-button" ><a href="/pressuser/analytics/view/all?subject={{ Crypt::encrypt($view->subject) }}">VIEW</a>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_analytics_view_all.css">
@endsection

@section("script")

@endsection