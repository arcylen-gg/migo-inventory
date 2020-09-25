@extends("press_user.member")
@section("pressview")
<div class="background-container">
    <div class="pressview">
         @if (Session::has('delete'))
              <div class="alert alert-danger">
                 <center>{{ Session::get('delete') }}</center>
              </div>
              @endif 
         <!-- Drafts -->
        <div class="drafts-holder-container">
          <div class="table-container">
            <table>
                <tr>    
                    <th>Press Release Title</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                @foreach($drafts as $draft)
                <tr>
                    <td>{{$draft->pr_headline}}</td>
                    <td>{{$draft->pr_date_sent}}</td>
                    <td>Draft</td>
                    <td><a href="/pressuser/pressrelease/edit_draft/{{$draft->pr_id}}">Edit</a></td>
                    <td><a href="/pressuser/pressrelease/delete_draft/{{$draft->pr_id}}">Delete</a></td>
                </tr>
                @endforeach
            </table>
          </div>
        </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_drafts.css">
@endsection

@section("script")

@endsection