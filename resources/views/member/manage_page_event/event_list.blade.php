@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-glass"></i>
            <h1>
            <span class="page-title">Event List</span>
            <small>
            Upcoming Events
            </small>
            </h1>
            <div class="dropdown pull-right">
                <a class="btn btn-primary popup" link="/member/page/events/create" size="lg"><i class="fa fa-star"></i> Create Event</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a data-toggle="tab" href="#all" class="cursor-pointer"><i class="fa fa-check"></i> Active</a></li>
        <li class="cursor-pointer " ><a data-toggle="tab" href="#archived" class="cursor-pointer"><i class="fa fa-trash"></i> Archived</a></li>
    </ul>
<!--     <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control">
                <option value="0">Filter Sample 001</option>
            </select>
        </div>
        <div class="col-md-5" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / number" aria-describedby="basic-addon1">
            </div>
        </div>
    </div> -->
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center" width="200px">Event Thumbnail</th>
                                    <th class="text-center" >Event Description</th>
                                    <th class="text-center" width="100px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($_event) > 0)
                                    @foreach($_event as $key => $event)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">
                                            <img class="match-height img-responsive" key="2"  src="{{$event->event_thumbnail_image}}" style="height: 100px;width:100px; object-fit: cover; border: 1px solid #ddd;margin:auto">
                                        </td>
                                        <td class="text-center">
                                            <div class="title">
                                                <h4>{{ucwords($event->event_title)}}</h4>
                                            </div>
                                            <div class="sub-title">{{date('M d, Y',strtotime($event->event_date))}}</div>
                                            <div>
                                                <a link="/member/page/events/reservee-list?id={{$event->event_id}}" class="popup" size="lg">{{number_format($event->reservee_total_count)}} Total Reserved</a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-custom">
                                                    <li><a class="popup" size="lg" link='/member/page/events/create?id={{$event->event_id}}'>Edit </a></li>
                                                    <li><a class="popup" size="md" link='/member/page/events/confirm-archived?id={{$event->event_id}}&action=archived'> Archived </a> </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr><td colspan="4" class="text-center">NO EVENT YET</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <div id="archived" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center" width="200px">Event Thumbnail</th>
                                    <th class="text-center" >Event Description</th>
                                    <th class="text-center" width="100px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($_event_archived) > 0)
                                    @foreach($_event_archived as $key => $event_archived)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">
                                            <img class="match-height img-responsive" key="2"  src="{{$event_archived->event_thumbnail_image}}" style="height: 100px;width:100px; object-fit: cover; border: 1px solid #ddd;margin:auto">
                                        </td>
                                        <td class="text-center">
                                            <div class="title">
                                                <h4>{{ucwords($event_archived->event_title)}}</h4>
                                            </div>
                                            <div class="sub-title">{{date('M d, Y',strtotime($event_archived->event_date))}}</div>
                                        </td>
                                        <td class="text-center">
                                           <a class="popup" size="md" link='/member/page/events/confirm-archived?id={{$event_archived->event_id}}&action=restore'> Restore </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr><td colspan="4" class="text-center">NO EVENT YET</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    function success_event(data)
    {        
        if(data.status)
        {
            toastr.success('Success');
            setInterval(function()
            {
                location.reload();
            },2000);       
        }
    }
</script>
@endsection