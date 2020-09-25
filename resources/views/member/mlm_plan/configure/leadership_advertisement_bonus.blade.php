@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Leadership Advertisement Bonus</span>
                <small>
                    You can set the computation of your leadership advertisement bonus plan here.
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
                                <form method="post" class="global-submit" action="/member/mlm/plan/leadership_advertisement_bonus/settings/submit" id="advertisement_settings">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12">
                                        <td colspan="4">
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-codensed">
                                                    <thead>
                                                        <tr>
                                                            <th>Left</th>
                                                            <th>Right</th>
                                                            <th>What level will start?</th>
                                                            <th>Bonus</th>
                                                            <th class="opt-col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($ldsetting)
                                                            <tr>
                                                                <td><input class="form-control" type="number" name="left" value="{{$ldsetting->left}}"></td>
                                                                <td><input class="form-control" type="number" name="right" value="{{$ldsetting->right}}"></td>
                                                                <td><input class="form-control" type="number" name="level_start" value="{{$ldsetting->level_start}}"></td>
                                                                <td><input class="form-control" type="text"   name="leadership_advertisement_income" value="{{$ldsetting->leadership_advertisement_income}}"></td>
                                                                <td><a href="javascript:" onClick="submit_advertisement()">Save</a></td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                                <td><input class="form-control" type="number" name="left" value="1"></td>
                                                                <td><input class="form-control" type="number" name="right" value="1"></td>
                                                                <td><input class="form-control" type="number" name="level_start" value="3"></td>
                                                                <td><input class="form-control" type="text"   name="leadership_advertisement_income" value="0"></td>
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
