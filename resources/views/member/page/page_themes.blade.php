@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Page <i class="fa fa-angle-double-right"></i> Themes</span>
                <small>
                    Pick a design for your website.
                </small>
            </h1>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th class="text-center">Theme Name</th>
                            <th class="text-center">Color Scheme</th>
                            <th class="text-center">Status</th>
                            <th class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_themes as $theme)
                        <tr>
                            <td class="text-center">{{ $theme["theme_name"] }}</td>
                            <td class="text-center">{{ $theme["color_scheme"] }}</td>
                            <td class="text-center">{!! $theme["active"] == 1 ? "<span style='color:green'>ACTIVE (" . strtoupper($theme_color) . ")</span>" : "INACTIVE" !!}</td>
                            <td class="text-center">
                                <!-- ACTION BUTTON -->
                                <div class="btn-group">
                                  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li><a class="popup" link="/member/page/themes/{{ $theme['key'] }}" size="md" href="javascript:">Activate Theme</a></li>
                                    <li><a href="#">Preview</li>
                                  </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
