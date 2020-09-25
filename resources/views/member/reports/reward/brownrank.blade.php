@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Brown Ranking</span>
            <small>
           		These are list of Brown Ranking
            </small>
            </h1>

            <div class="dropdown pull-right">
                <button onclick="action_load_link_to_modal('/member/report/reward-brown-rank-compute','lg')" class="btn btn-def-white btn-custom-white"><i class="fa fa-check"></i> Re-Compute Ranking</button>
                <button onclick="location.href='/member/report/reward-brown-rank-excel'" class="btn btn-primary"><i class="fa fa-file-excel-o"></i>&nbsp; Export to Excel</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray"  style="margin-bottom: -10px;">
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control change-rank">
            	@foreach($_rank as $rank)
                <option {{ request('rank_id') == $rank->rank_id ? 'selected' : '' }} value="{{ $rank->rank_id }}">{{ $rank->rank_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3" style="padding: 10px">

        </div>
        <div class="col-md-2" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / number" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center">SLOT NO.</th>
                                    <th class="text-center">CUSTOMER NAME</th>
                                    <th class="text-center" width="120px">E-Mail</th>
                                    <th class="text-center" width="120px">Contact Number</th>
                                    <th class="text-center" width="120px">Current Rank</th>
                                    <th class="text-center" width="120px">RANK STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach($_slot as $slot)
                                <tr>
                                    <td class="text-center">{{ $slot->slot_no }}</td>
                                    <td class="text-center">{{ $slot->first_name }} {{ $slot->last_name }}</td>
                                    <td class="text-center">{{ $slot->email }}</td>
                                    <td class="text-center">{{ ($slot->contact == "" ? $slot->customer_mobile : $slot->contact) }}</td>
                                    <td class="text-center">{{ $slot->brown_current_rank }}</td>
                                    <td class="text-center">{!! $slot->brown_next_rank_requirements !!} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function()
    {
        $(".change-rank").change(function(e)
        {
            window.location.href = '/member/report/reward-brown-rank?rank_id=' + $(e.currentTarget).val();
        })
    });
</script>
@endsection