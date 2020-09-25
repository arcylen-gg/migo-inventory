@extends("press_user.member")
@section("pressview")
<div class="background-container">
    <div class="pressview">
        <div class="dashboard-container">
            <!-- Dashboard -->
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="title-container">Overall Analytic Results</div>
                </div>
                <div class="pull-right">
                    <div class="button-container">
                        <span class="create-button" ><a href="/pressuser/analytics/view">View Details</a>
                    </div>
                </div>
            </div>
            <div class="table-container">
              <table style="width:100%;" >
                    <tr>
                      <th style="text-align: center;width: 25%">ACTION</th>
                      <th style="text-align: center;width: 25%">RESULTS</th>
                    </tr>
                    <tr>
                      <td>Sent</td>
                      <td>{{isset(session('share_analytics')->sent) ? session('share_analytics')->sent : 0}}</td>
                    </tr>
                    <tr>
                      <td>Email Open</td>
                      <td>{{isset(session('share_analytics')->opens) ? session('share_analytics')->opens : 0}}</td>
                    </tr>
                    <tr>
                      <td>Clicks</td>
                      <td>{{isset(session('share_analytics')->clicks) ? session('share_analytics')->clicks : 0}}</td>
                    </tr>
                    <tr>
                      <td>Reject</td>
                      <td>{{isset(session('share_analytics')->rejects) ? session('share_analytics')->rejects : 0}}</td>
                    </tr>
                    <tr>
                      <td>Unsubscribe</td>
                      <td>{{isset(session('share_analytics')->unsubs) ? session('share_analytics')->unsubs : 0}}</td>
                    </tr> 
                    <tr>
                      <td>Soft Bounce</td>
                      <td>{{isset(session('share_analytics')->soft_bounces) ? session('share_analytics')->soft_bounces : 0}}</td>
                    </tr>
                    <tr>
                      <td>Hard Bounce</td>
                      <td>{{isset(session('share_analytics')->hard_bounces) ? session('share_analytics')->hard_bounces : 0}}</td>
                    </tr>
              </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_analytics.css">
@endsection

@section("script")

@endsection