@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Advertisement Bonus</span>
                <small>
                    You can set the computation of your advertisement bonus plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right"> < Back</a>
        </div>
    </div>
</div>

<div class="bsettings">
    {!! $basic_settings !!}  
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
           <div class="table-responsive" >
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td>
                                <form method="post" class="global-submit" action="/member/mlm/plan/advertisement_bonus/settings/submit" id="advertisement_settings">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12">
                                        <td colspan="4">
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-codensed">
                                                    <thead>
                                                        <tr>
                                                            <th>From what level</th>
                                                            <th>Bonus</th>
                                                            <th>Bonus GC</th>
                                                            <th class="opt-col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($adsetting)
                                                            <tr>
                                                                <td><input class="form-control" type="number" name="level_end" value="{{$adsetting->level_end}}"></td>
                                                                <td><input class="form-control" type="text"   name="advertisement_income" value="{{$adsetting->advertisement_income}}"></td>
                                                                <td><input class="form-control" type="text"   name="advertisement_income_gc" value="{{$adsetting->advertisement_income_gc}}"></td>
                                                                <td><a href="javascript:" onClick="submit_advertisement()">Save</a></td>
                                                            </tr>
                                                            @else
                                                              <tr>
                                                                <td><input class="form-control" type="number" name="level_end" value="2"></td>
                                                                <td><input class="form-control" type="text"   name="advertisement_income" value="0"></td>
                                                                <td><input class="form-control" type="text"   name="advertisement_income_gc" value="0"></td>
                                                                <td><a href="javascript:" onClick="submit_advertisement()">Save</a></td>
                                                            </tr>                      
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>                
@endsection

@section('script')
<script type="text/javascript">
    function submit_advertisement()
    {
        $('#advertisement_settings').submit();
    }
</script>
@endsection
