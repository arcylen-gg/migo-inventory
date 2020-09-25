<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
@if($slot)
<html>
    <head>
        <base href="{{URL::to('/')}}">
        <!-- <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script> -->
        <script type="text/javascript" src="/assets/external/jquery.minv1.js"></script>
        <script type="text/javascript" src="/assets/slot_genealogy/genealogy/drag.js"></script>
        <link rel="stylesheet" href="/assets/mlm/animate/css/animate.min.css">
        <!-- <link rel="stylesheet" href="/assets/mlm/animate/css/normalize.min.css"> -->
        <link rel="stylesheet" type="text/css" href="/assets/slot_genealogy/member/css/genealogy.css" />
        <link rel="stylesheet" type="text/css" href="/assets/mlm/css/genealogy.css">
        <!-- <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css"> -->
        <!-- <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css"> -->
        <!-- <link rel="stylesheet" href="/assets/member/css/member.css" type="text/css"/> -->
        <title>Genealogy</title>
    </head>
    <body id="body" class="body" style="height: 100%;">
        <!-- backup style="height: 100%;" -->
        <div class="overscroll" style="width: 100%; height: inherit; overflow: auto;">

            <!-- back up  style="width: 100%; height: inherit; overflow: auto;" -->
            <div class="tree-container" style="width: 5000%; padding-left: 20px; height: 5000%;">

            <!-- back up style="width: 5000%; padding: 20px; height: 5000%;" -->
                <div class="tree">
                    <ul>
                        <li class="width-reference">
                            <span class="downline parent parent-reference PS" x="{{ $slot->slot_id }}">   
                                <div id="info">
                                    @if($slot->profile == null)
                                    <div id="photo">
                                        <img src="/assets/slot_genealogy/member/img/default-image.jpg">
                                    </div>
                                    @else
                                    <div id="photo">
                                        <img src="{{$slot->profile}}">
                                    </div>
                                    @endif
                                    <div id="cont">
                                        <div>{{ strtoupper($slot->first_name) }}</div>
                                        <b>{{ $slot->membership_name }}</b>
                                    </div>
                                    <div>{{ $slot->slot_status }}</div>
                                    @if($format == 'sponsor')
                                    
                                    @else
                                        <div>Count L:{{$l}} R:{{$r}}</div>
                                        <div>Points - L: {{$slot->slot_binary_left}} R: {{$slot->slot_binary_right}}</div>
                                    @endif
                                </div>
                                <div class="id" >{{ $slot->slot_no }}</div>
                            </span> 
                            <i class="downline-container">
                                {!! $downline !!}
                            </i>                  
                        </li>
                    </ul>       
                </div>
            </div>
        </div> 

        <div id="animatedModal">
            <!--THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID  class="close-animatedModal" -->
            <div class="close-animatedModal"> 
                <center><span id="rcorners2">✖</span></center>
            </div>
                
            <div class="modal-content modal_append_add_slot">
                      
            </div>
        </div>  
    </body>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <script type="text/javascript" src="/assets/mlm/animate/js/animatedModal.min.js"></script>
    <script type="text/javascript">
        var mode = "{{ Request::input('mode') }}";
        var g_width = 0;
        var half;
        mobile_top = 0;
        mobile_left = $(window).width() * 2500;

        $(document).on('touchstart', '.overscroll', function(event) 
        {
            // event.preventDefault();
            // $(".overscroll").removeOverscroll();
            // if (touched == 0) 
            // {
                setTimeout(
                  function() 
                  {
                    $(".overscroll").overscroll(
                    {
                        cancelOn: '.no-drag',
                        scrollLeft: mobile_left,
                        scrollTop: mobile_top
                    })
                  }, 140);
            // }
        });

        $(document).on('touchend', '.overscroll', function(event) 
        {
            mobile_top = $(".overscroll .tree-container").position().top;
            mobile_left = $(".overscroll .tree-container").position().left;
        });

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
        $('.add_slot_membership_code').on('click', function (){
            var position = $(this).attr('position');
            console.log("position", position);
            var placement = $(this).attr('placement');
            console.log("placement", placement);
        });
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
                            url:"/mlm/slot/genealogy/downline",
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
    <script>
        initialize_add_Slot(); 
        var placement_global = 0;
        var slot_id_a = '{{$slot_id_a}}';
        function initialize_add_Slot()
        {
            // $("#add_slot_modal_open_Right").animatedModal({ color : '#F1F1F1'});
            // $("#add_slot_modal_open_Left").animatedModal({ color : '#F1F1F1'});
            $('#add_slot_modal_open_Left').each(function (index){
                $(this).animatedModal({ color : '#F1F1F1'});
             });
            $('#add_slot_modal_open_Right').each(function (index){
                $(this).animatedModal({ color : '#F1F1F1'});
             });

            $('#add_slot_modal_open_Right').on('click', function(){
                var position = $(this).attr('position');
                console.log("position", position);
                var placement = $(this).attr('placement');
                console.log("placement", placement);
                $('.modal_append_add_slot').html("<center><div><img src='/assets/slot_genealogy/member/img/485.gif'></div></center>");
                $('.modal_append_add_slot').load('/mlm/slot/add?position=' + position + '&placement=' + placement + '&slot_sponsor=' + slot_id_a);
            });

            $('#add_slot_modal_open_Left').on('click', function(){
                var position = $(this).attr('position');
                console.log("position", position);
                var placement = $(this).attr('placement');
                placement_global = placement;
                console.log("placement", placement);
                $('.modal_append_add_slot').html("<center><div><img src='/assets/slot_genealogy/member/img/485.gif'></div></center>");
                $('.modal_append_add_slot').load('/mlm/slot/add?position=' + position + '&placement=' + placement + '&slot_sponsor=' + slot_id_a);
                
            });
        }
        $(document).on("submit", ".global-submit", function(e)
        {
            var data = $(e.currentTarget).serialize();
            var link = $(e.currentTarget).attr("action");
            var modal = $(e.currentTarget).closest('.modal');
            $('#rcorners3').attr('disabled','disabled');
            action_global_submit(link, data, modal);
            e.preventDefault();
        })
        function action_global_submit(link, data, modal)
        {
            $.ajax({
                url:link + '?disable_session=true_a',
                dataType:"json",
                data:data,
                type:"post",
                success: function(data)
                {
                    submit_done_genealogy(data);
                    $('#rcorners3').removeAttr('disabled');
                    
                }
            })
        }

        function submit_done_genealogy(data)
        {
            // 
            // $('#rcorners3').prop('disabled', false);
            // document.getElementById("rcorners3").disabled=false;
            if(data.response_status == "warning")
            {
                var erross = data.warning_validator;
                var error_append = '<ul>';
                $.each(erross, function(index, value) 
                {
                    // toastr.warning(value);
                    error_append += '<li>'+value+'</li>';
                    // $('.append_error').append(value);
                }); 
                error_append += '</ul>';
                $('.append_error').html(error_append);
            }
            else if(data.response_status == 'warning_2')
            {
                // toastr.warning(data.error);
                $('.append_error').html(data.error);
            }
            else if(data.response_status == 'success_add_slot')
            {
                // location.reload();
                 $('.append_error').html('Success <br>');
                 $('#rcorners2').click();

                 $('.parent-reference').each(function (index){
                    var x = $(this).attr('x');
                    if(x == placement_global)
                    {
                        $(this).click(); 
                    }
                 });
            }
        }
    </script>
    
</html>
@else
You have no slots available.
@endif
