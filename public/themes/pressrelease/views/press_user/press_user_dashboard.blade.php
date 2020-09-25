@extends("press_user.member")
@section("pressview")
<div class="background-container">
    <div class="pressview">
        <div class="dashboard-container">
            <!-- Dashboard -->
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="title-container">RECENT RELEASES</div>
                </div>
                <div class="pull-right">
                    <div class="button-container">
                        <span class="create-button" ><a href="/pressuser/pressrelease">Create a Press Release</a>
                    </div>
                </div>
            </div>
           <div class="table-container">
              <table>
                  <tr>
                      <th>Press Release Title</th>
                      <th>Publish Date</th>
                      <th>Status</th>
                  </tr>
                  @foreach($pr as $prs)
                  <tr>
                      <td>{{$prs->pr_headline}}</td>
                      <td>{{$prs->pr_date_sent}}</td>
                      <td>{{$prs->pr_status}}</td>
                  </tr>
                  @endforeach
              </table> 
           </div>
        </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_dashboard.css">
@endsection

@section("script")

@endsection