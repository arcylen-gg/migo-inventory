@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Membership</span>
                <small>
                    The customer can create slot using different kinds of membership.
                </small>
            </h1>
            <a href="/member/mlm/membership/add" class="panel-buttons btn btn-primary pull-right btn-custom-primary">Add Membership</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->


<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#active"><i class="fa fa-star"></i> Active Membership</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#archive"><i class="fa fa-trash"></i> Archived Membership</a></li>
    </ul>
    
    <div class="tab-content">
        <div id="active" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Membership Name</th>
                            <th>Membership Price</th>
                            <th>Membership Rank</th>
                            <th>Packages</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 0;?>
                        @if($membership_active->first())
                            @foreach($membership_active as $active)
                                <tr >
                                    <td style="font-weight: bold;">{{$active->membership_name}}</td>
                                    <td>{{currency('PHP', $active->membership_price)}}</td>
                                    <td>{{ordinal($rank = $rank + 1)}}</td>
                                    <td class="text-left">
                                        @if($active->package_count == 0)
                                        <a href="/member/mlm/membership/edit/{{$active->membership_id}}#addnewpackage"><span style="color: red;">No Package Yet</span></a>
                                        @else
                                        {{$active->package_count}} Package/s
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/member/mlm/membership/edit/{{$active->membership_id}}">EDIT</a> |
                                        <a href="/member/mlm/membership/delete/{{$active->membership_id}}">DELETE</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5"><center>No Active Membership</center></td>    
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="archive" class="tab-pane fade in ">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Membership Name</th>
                            <th>Membership Price</th>
                            <th>Membership Rank</th>
                            <th>Packages</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($membership_archive->first())
                            @foreach($membership_archive as $archive)
                                <tr >
                                    <td style="font-weight: bold;">{{$archive->membership_name}}</td>
                                    <td>{{currency('PHP', $archive->membership_price)}}</td>
                                    <td>N/A</td>
                                    <td class="text-left">
                                        @if($archive->package_count == 0)
                                        <a href="/member/mlm/membership/edit/{{$archive->membership_id}}"><span style="color: red;">No Package Yet</span></a></td>
                                        @else
                                        {{$archive->package_count}} Package/s
                                        @endif
                                    <td>
                                        <a href="/member/mlm/membership/edit/{{$archive->membership_id}}">EDIT</a> |
                                        <a href="/member/mlm/membership/restore/{{$archive->membership_id}}">RESTORE</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5"><center>No Trashed Membership</center></td>    
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
</div>

@endsection
@section('script')
<script type="text/javascript">
@if (Session::has('success'))
   toastr.success("{{ Session::get('success') }}");
@endif	
@if (Session::has('warning'))
   toastr.warning("{{ Session::get('warning') }}");
@endif	
</script>
@endsection
