@extends('member.layout')

@section('content')
<form class="global-submit" action="">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Settings</span>
                    <small>
                    </small>
                </h1>
                <a href="javascript:" class="panel-buttons btn btn-custom-primary pull-right" >Save</a>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray ">
        <ul class="nav nav-tabs">
            <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-star"></i> First</a></li>
            <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-trash"></i> Second</a></li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane fade in active">
                
            </div>
        </div>
    </div>
</form>
@endsection
