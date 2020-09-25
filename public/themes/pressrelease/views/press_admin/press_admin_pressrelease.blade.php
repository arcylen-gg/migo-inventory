@extends("press_admin.admin")
@section("pressview")
<div class="background-container">
    <div class="pressview">
        <div class="press-container">
            <div class="title-container">Member's Press Release</div>
            <table>
                <tr>
                    <th>Member's Name</th>
                    <th>Press Release Title</th>
                    <th>Publish Date</th>
                    <th>Sent To</th>
                </tr>
                <tr>
                    <td>Member 1</td>
                    <td>Sample 1</td>
                    <td>15/11/2017</td>
                    <td>Contact 1</td>
                </tr>
                <tr>
                    <td>Member 2</td>
                    <td>Sample 2</td>
                    <td>16/11/2017</td>
                    <td>Contact 2</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_admin_pressrelease.css">
@endsection

@section("script")

@endsection