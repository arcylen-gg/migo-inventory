@extends("member.member_layout")

@section("member_content")
<input type="hidden" value="{{$_points}}" class="hidden_points_redeemable">
<div class="report-container" style="overflow: hidden;">
    <div class="report-header clearfix">
        <div class="animated fadeInLeft left">
            <div class="icon">
                <h1><i class="fa fa-gift"></i></h1>
            </div>
            <div class="text">
                    <div class="name">{{$page}}</div>
                    <div class="sub">Redeemable items are shown here. </div>
            </div>
        </div>
        <div class="animated fadeInRight right">
            <!-- <div class="search">
                <select class="form-control">
                    <option>All Slots</option>
                </select>
            </div> -->
        </div>
    </div>
    <br>
    <div class="form-group">
        <div class="col-md-12">
            <label for="basic-input">Slot</label>
            <select name="slot" class="slot-owner">
                @foreach($slot as $s)
                <option class="form-control" value="{{$s->slot_no}}">{{$s->slot_no}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br>
    <div class="report-content">
        
        <div class="col-md-12 points-here">
            <h4>
                <b>Remaining Points: <span style="font-weight: 300;">{{currency('',$_points)}} POINT(S)</span></b>
            </h4>             
        </div>
        <div class="animated fadeInUp holder">
            {{-- <center> --}}
            <div class="row clearfix">
                {{-- <center> --}}
                @foreach($_redeemable as $redeemable)
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="item-redeemable">
                        <div class="image-container match-height">
                            <img src="{{$redeemable->image_path}}">
                        </div>
                        @if(strlen($redeemable->item_description)>30)
                        <div class="item-name"><h4><b>{{substr($redeemable->item_name,0,27)."..."}}</b></h4></div>
                        @else
                        <div class="item-name"><h4><b>{{$redeemable->item_name}}</b></h4></div>
                        @endif
                        <div class="bottom-text"><label>{{currency('',$redeemable->redeemable_points)." points"}}</label></div>
                        <!-- @if(strlen($redeemable->item_description)>30)
                        <div><label>{{substr($redeemable->item_description,0,27)."..."}}</label></div>
                        @else
                        <div><label>{{$redeemable->item_description}}</label></div>
                        @endif -->
                        <button class="btn-primary btn-primary btn-custom-primary redeem_item" item_name="{{$redeemable->item_name}}" redeemable_points="{{$redeemable->redeemable_points}}" item_description="{{$redeemable->item_description}}" item_redeem_id="{{$redeemable->item_redeemable_id}}" type="button">View</button>
                    </div>
                </div>
                @endforeach
                {{-- </center> --}}

                @if(count($_redeemable)<1)
                <div class="col-md-12">
                    <center>
                        <h3>No Items available</h3>
                    </center>
                </div>
                @endif
                
            </div>
            {{-- </center> --}}
        </div>
    </div>

</div>

<div id="redeem-modal" class="modal redeem-modal fade">
    <div class="modal-sg modal-dialog">
        <div class="modal-content">
            <form action="/members/redeem-item" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="item_id" value="0" class="hidden_item_redeemable_id">
                <input type="hidden" name="slot_no" class="hidden-slot-no">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"><i class="fa fa-qrcode"></i> You are about to redeem this item:</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="row">
                        <div class="clearfix modal-body"> 
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <label for="basic-input">Information:</label>  
                                        <div>
                                            Item Name:<span class="item_name"></span>
                                        </div>    
                                        <div>
                                            Description:<span class="description"></span>
                                        </div>    
                                        <div>
                                            Points:<span class="points"></span>
                                        </div>      
                                        <div>
                                            Current Points:<span class="c_points"></span>
                                        </div>      
                                        <div>
                                            Points After Redeeming:<span class="n_points"></span>
                                        </div>  
                                    </div>
                                </div>

                                

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary btn-custom-primary redeemable_submit" type="submit">Redeem</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section("member_script")
<script type="text/javascript">
    var redeemable_points = $(".hidden_points_redeemable").val();
    $(".redeem_item").click(function()
    {
        $remaining_points = redeemable_points - $(this).attr("redeemable_points");

        $(".hidden_item_redeemable_id").val($(this).attr("item_redeem_id"));
        $(".item_name").text($(this).attr("item_name"));
        $(".description").text($(this).attr("item_description"));
        $(".points").text($(this).attr("redeemable_points"));
        $(".c_points").text(redeemable_points);
        $(".n_points").text($remaining_points);
        $(".redeem-modal").modal("show");

        if($remaining_points >= 0)
        {
            $(".redeemable_submit").prop("disabled", false);
        }
        else
        {
            $(".redeemable_submit").prop("disabled", true);
        }
    });

</script>
<script type="text/javascript">
    @if(Session::get("response")=='success')
    toastr.success("Item redeemed");
    @elseif(Session::get("response")=='error')
    toastr.error("no stock available");
    @endif
</script>
<script type="text/javascript" src="/themes/philtech/js/redeemable.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
@endsection

@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/redeemable.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    select
    {
        width: 40%;
    }
</style>
@endsection


<!-- <table class="table">
                    <thead>
                        <tr>
                            @if(count($_redeemable)>0)
                            <th class="text-center" width="100px"></th>
                            <th class="text-center" width="200px">ITEM NAME</th>
                            <th class="text-left" width="100px">DESCRIPTION</th>
                            <th class="text-right" width="200px">REDEEM POINTS</th>
                            <th class="text-center" width="100px"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_redeemable) > 0)
                            @foreach($_redeemable as $redeemable)
                            <tr>
                                <td class="text-center">
                                    <img src="{{$redeemable->image_path}}" class="img-redeemable">
                                </td>
                                <td class="text-center">
                                    <div><b>{{ $redeemable->item_name }}</b></div>
                                </td>
                                <td class="text-left">{{$redeemable->item_description}}</td>
                                <td class="text-right"><b>{{$redeemable->redeemable_points}}</b></td>
                                <td class="text-right"><button class="btn-primary btn-primary btn-custom-primary" type="submit">Redeem</button></td>
                            </tr>
                            @endforeach
                        @else
                            <tr class="text-center" >
                                <td colspan="4">NO REDEEMABLE YET</td>
                            </tr>
                        @endif
                    </tbody>
                </table> -->