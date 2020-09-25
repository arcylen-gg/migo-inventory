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
                    <div class="title-container">Details</div>
                </div>
                <div class="pull-right">
                    <div class="button-container">
                        <span class="create-button" ><a href="/pressuser/analytics/view">BACK</a>
                    </div>
                </div>
            </div>
            <div class="table-view">
              <table style="width:100%;">    
                    <tr>
                      <th style="text-align: center;width: 20%">Date / Time </th>
                      <th style="text-align: center;width: 20%">Title / Subject</th>
                      <th style="text-align: center;width: 10%">Status </th>
                      <th style="text-align: center;width: 25%">Recipients</th>
                      <th style="text-align: center;width: 15%">No. Email Open</th>
                      <th style="text-align: center;width: 15%">Clicks</th>
                    </tr>
                    @foreach($analytics_view as $view)
                    <tr>
                      <td>{{date("m-d-Y\ / h:i:s a",($view->ts))}} </td>
                      <td>{{$view-> subject}}</td>
                      <td>{{$view-> state}}</td>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_analytics_view.css">
@endsection

@section("script")

@endsection