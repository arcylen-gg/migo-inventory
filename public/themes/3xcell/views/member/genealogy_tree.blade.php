<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
@if($slot)
<html>
    <head>
        <base href="{{URL::to('/')}}">
        <!-- <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script> -->
        <script type="text/javascript" src="/assets/external/jquery.minv1.js"></script>
        <script type="text/javascript" src="/assets/slot_genealogy/genealogy/drag.js"></script>
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/old_genealogy.css" />

        <!-- Trial-->
        <link rel="stylesheet" href="/resources/assets/remodal/src/jquery.remodal.css">
        <link rel="stylesheet" href="/resources/assets/remodal/src/remodal-default-theme.css">
        <link rel="stylesheet" href="/assets/external/chosen/chosen/chosen.css" media="screen"/>
        <style type="text/css">
        </style>
        <title>Genealogy</title>
    </head>
    <body id="body" class="body" style="height: 100%;">
        <!-- backup style="height: 100%;" -->
        <div class="overscroll" style="width: 100%; height: inherit; overflow: auto;">

            <!-- back up  style="width: 100%; height: inherit; overflow: auto;" -->
            <div class="tree-container" style="width: 5000%; padding-left: 20px  ; height: 5000%;">

            <!-- back up style="width: 5000%; padding: 20px; height: 5000%;" -->
                <div class="tree">
                    <ul>
                        <li class="width-reference">
                            @if(isset($genealogy_color))
                            <span class="downline parent parent-reference PS" x="{{ $slot->slot_id }}" style="{{$genealogy_color}};{{$genealogy_border_color}}">   
                            @else
                            <span class="downline parent parent-reference PS" x="{{ $slot->slot_id }}">   
                            @endif 
                                <div id="info">
                                    <div id="photo">
                                        {{-- <img src="/assets/slot_genealogy/member/img/default-image.jpg"> --}}
                                        <img style="border-radius: 100%;" src="{{ $profile_image }}">
                                    </div>
                                    <div id="cont">
                                        <div>{{ strtoupper($slot->first_name) }}</div>
                                        <b>{{ $slot->membership_name }}</b>
                                    </div>
                                    <div>{{ $slot->slot_status }}</div>
                                    @if($format == 'sponsor')
                                    
                                    @else
                                        <div>L:{{$l}} R:{{$r}}</div>
                                    @endif

                                    @if(isset($rank_points["rpersonal_pv"]))
                                        <div>&nbsp</div>
                                        <div>&nbsp</div>
                                        <div>Rank PV:{{$rank_points["rpersonal_pv"]}}</div>
                                        <div>Rank Group PV:{{$rank_points["rgroup_pv"]}}</div>
                                    @endif
                                </div>
                                <div class="id">
                                    YOU
                                </div>
                            </span> 
                            <i class="downline-container">
                                {!! $downline !!}
                            </i>                  
                        </li>
                    </ul>       
                </div>
            </div>
        </div>   
        <div class="remodal slot_add_remodal" data-remodal-id="modal">
            <center>Loading..</center>
        </div>
    </body>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <!-- Trial -->
    <script src="/resources/assets/remodal/src/jquery.remodal.js"></script>
    <script type="text/javascript" src="/assets/external/chosen/chosen/chosen.jquery.js"></script> 
    <script type="text/javascript">
        var mode = "{{ Request::input('mode') }}";
        var g_width = 0;
        var half;
        // setTimeout(
        // function() {
        //   $('[data-remodal-id=modal]').remodal().close();
        // }, 2000);
        
        // function sponsor_click(slot_id)
        // {
        //     console.log(slot_id);
        //     // /member/mlm/slot/add
        //     $('.slot_add_remodal').load('/member/mlm/slot/add?sponsor=' + slot_id);
        //     var inst = $('[data-remodal-id=modal]').remodal();
        //     inst.open();
        // }
        $(document).ready(function()
        {  

            $("li").show();   
            half = $(window).width() * 2500;
            g_width = $(".width-reference").width();
            $margin_left = ($(window).width() - $(".width-reference").width()) / 2;
            $(".tree-container").css("padding-left",half  + $margin_left);
            $(".overscroll").height($(document).height());
            
            
            $parent_position = $(".parent").position();
            $window_size = $(window).width();
            
            $(function(o){
                o = $(".overscroll").overscroll(
                {
                    cancelOn: '.no-drag',
                    scrollLeft: half,
                    scrollTop: 0
                });
                $("#link").click(function()
                {
                    if(!o.data("dragging"))
                    {
                        console.log("clicked!");
                    }
                    else
                    {
                        return false;
                    }
                });
            }); 
            
            
        })
        
        var genealogy_loader = new genealogy_loader();
        function genealogy_loader()
        {
            init();
            function init()
            {
                $(document).ready(function()
                { 
                    document_ready();
                });
            }
            function document_ready()
            {
                add_click_event_to_downlines();
            }
            function add_click_event_to_downlines()
            {      
                $(".downline").unbind("click");
                $(".downline").bind("click", function(e)
                {
                    $currentScroll = $(".overscroll").scrollLeft();
                    
                    if($(e.currentTarget).siblings(".downline-container").find("ul").length == 0)
                    {
                        $(e.currentTarget).append("<div class='loading'><img src='/assets/slot_genealogy/member/img/485.gif'></div>");
                        $(".downline").unbind("click");
                        
                        $genealogy = $(e.currentTarget).attr("genealogy_function");
                        $networker_account_id = $(e.currentTarget).attr("x");
                        
                        $.ajax(
                        {
                            url:"/members/genealogy-downline",
                            dataType:"json",
                            data:{x:$networker_account_id,complan_library:$genealogy,mode:mode},
                            type:"get",
                            success: function(data)
                            {    
                                $(".loading").remove();
                                $(e.currentTarget).siblings(".downline-container").append(data);
                                $(e.currentTarget).siblings(".downline-container").find("li").fadeIn();
                                adjust_tree();
                                add_click_event_to_downlines();
                                adjust_tree();
                                //$(e.currentTarget).parent("li").siblings("li").find("ul").remove();
                            }
                        });    
                    }
                    else
                    {
                        $(e.currentTarget).siblings(".downline-container").find("ul").fadeOut(function()
                        {
                            this.remove();
                            adjust_tree();    
                        });
                        
                    }
                });    
            }     
            function adjust_tree()
            {
                    $curr_margin_left = parseFloat($(".tree-container").css("padding-left"));   
                    $deduction = parseFloat(($(".width-reference").width() - g_width) / 2);
                    g_width = $(".width-reference").width();
                    $(".tree-container").css("padding-left", parseFloat($curr_margin_left  - $deduction));
            }
        }
    </script>
</html>
@else
You have no slots available.
@endif
