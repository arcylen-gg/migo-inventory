@extends("layout")
@section("content")
<div class="container">
    <div id="products" class="row list-group">
    @foreach($company as $company)
        <div class="item  col-xs-3 col-lg-4">
            <div class="thumbnail">
                <img class="group list-group-image" img src ="{{ $company->company_logo }}" />
                <div class="caption">
                    <h4 class="group inner list-group-item-heading">
                        <center>{{ $company->company_name }}</center></h4>
                    <p class="group inner list-group-item-text">
                        <center>{{ $company->company_address }}</center></p>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <p class="lead">
                                <center>{{ $company->company_number }}</center></p>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <a class="btn btn-success" href="http://www.jquery2dotnet.com">View Location</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@section("css")
@endsection

<style>
.glyphicon { margin-right:5px; }
.thumbnail
{
    margin-bottom: 20px;
    padding: 10px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    border-radius: 0px;
    width: 300px; 
    height: 450px;
 
}

.item.list-group-item
{
    float: none;
    width: 100%;
    background-color: #fff;
    margin-bottom: 10px;
    
}
.item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover
{
    background: #428bca;
    
}

.item.list-group-item .list-group-image
{
    margin-right: 10px;
    width: 50% !important;
    height: auto;

}
.item.list-group-item .thumbnail
{
    margin-bottom: 0px;
    width: 20; 

}
.item.list-group-item .caption
{
    padding: 9px 9px 0px 9px;
}
.item.list-group-item:nth-of-type(odd)
{
    background: #eeeeee;

}

.item.list-group-item:before, .item.list-group-item:after
{
    display: table;
    content: " ";


}

.item.list-group-item img
{
    float: left;
    height:50px;
    width: 20px; 


}
.item.list-group-item:after
{
    clear: both;

}
.list-group-item-text
{
    margin: 0 0 11px;
    text-align: center;	
    overflow: hidden;

}
.list-group-item {
    width: 30px;
    height: 30px;
}
#products{
	margin-top: 50px
	overflow: hidden;
}
</style>

<script>
$(document).ready(function() {
    $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
    $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});
});
</script>
