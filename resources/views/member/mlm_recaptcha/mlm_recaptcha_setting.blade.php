<style>
.timerangepicker-container{display:flex;position:absolute}.timerangepicker-label{display:block;line-height:2em;background-color:#c8c8c880;padding-left:1em;border-bottom:1px solid grey;margin-bottom:.75em}.timerangepicker-from,.timerangepicker-to{border:1px solid grey;padding-bottom:.75em}.timerangepicker-from{border-right:none}.timerangepicker-display{box-sizing:border-box;display:inline-block;width:2.5em;height:2.5em;border:1px solid grey;line-height:2.5em;text-align:center;position:relative;margin:1em .175em}.timerangepicker-display .decrement,.timerangepicker-display .increment{cursor:pointer;position:absolute;font-size:1.5em;width:1.5em;text-align:center;left:0}.timerangepicker-display .increment{margin-top:-.25em;top:-1em}.timerangepicker-display .decrement{margin-bottom:-.25em;bottom:-1em}.timerangepicker-display.hour{margin-left:1em}.timerangepicker-display.period{margin-right:1em}
.timerangepicker-container
{
    background-color: #fff;
}
</style>

<form class="global-submit form-horizontal" role="form" action="/member/mlm/recaptcha/recaptcha_setting" method="post">
    {{csrf_field()}}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{$page}}</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="form-group">
            <div class="col-md-12">
                <label for="basic-input">Points</label>
                <input autocomplete="off" id="basic-input" type="text" value="{{$point}}" class="form-control" name="point" placeholder="Acquired points per submit">
            </div>
        </div>            
        <div class="form-group">
            <div class="col-md-12">
                <label for="basic-input">Schedule</label>
                <div id="datetimepickerDate" class="input-group timerange">
                    <input class="form-control" type="text" name="schedule">
                    <span class="input-group-addon" style="">
                        <i aria-hidden="true" class="fa fa-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Set</button>
    </div>
</form>
<script type="text/javascript">
    function success_setting(data)
    {
        toastr.success('success');
        recaptcha.action_load_points();
        data.element.modal('hide');
    }
    function error(data)
    {
        toastr.error('Error updating setting');
    }
</script>

<script>$('.timerange').on('click',function(e){e.stopPropagation();var input=$(this).find('input');var now=new Date();var hours=now.getHours();var period="PM";if(hours<12){period="AM"}else{hours=hours-11}
var minutes=now.getMinutes();var range={from:{hour:hours,minute:minutes,period:period},to:{hour:hours,minute:minutes,period:period}};if(input.val()!==""){var timerange=input.val();var matches=timerange.match(/([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)-([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)/);if(matches.length===7){range={from:{hour:matches[1],minute:matches[2],period:matches[3]},to:{hour:matches[4],minute:matches[5],period:matches[6]}}}};console.log(range);var html='<div class="timerangepicker-container">'+'<div class="timerangepicker-from">'+'<label class="timerangepicker-label">From:</label>'+'<div class="timerangepicker-display hour">'+'<span class="increment fa fa-angle-up"></span>'+'<span class="value">'+('0'+range.from.hour).substr(-2)+'</span>'+'<span class="decrement fa fa-angle-down"></span>'+'</div>'+':'+'<div class="timerangepicker-display minute">'+'<span class="increment fa fa-angle-up"></span>'+'<span class="value">'+('0'+range.from.minute).substr(-2)+'</span>'+'<span class="decrement fa fa-angle-down"></span>'+'</div>'+':'+'<div class="timerangepicker-display period">'+'<span class="increment fa fa-angle-up"></span>'+'<span class="value">PM</span>'+'<span class="decrement fa fa-angle-down"></span>'+'</div>'+'</div>'+'<div class="timerangepicker-to">'+'<label class="timerangepicker-label">To:</label>'+'<div class="timerangepicker-display hour">'+'<span class="increment fa fa-angle-up"></span>'+'<span class="value">'+('0'+range.to.hour).substr(-2)+'</span>'+'<span class="decrement fa fa-angle-down"></span>'+'</div>'+':'+'<div class="timerangepicker-display minute">'+'<span class="increment fa fa-angle-up"></span>'+'<span class="value">'+('0'+range.to.minute).substr(-2)+'</span>'+'<span class="decrement fa fa-angle-down"></span>'+'</div>'+':'+'<div class="timerangepicker-display period">'+'<span class="increment fa fa-angle-up"></span>'+'<span class="value">PM</span>'+'<span class="decrement fa fa-angle-down"></span>'+'</div>'+'</div>'+'</div>';$(html).insertAfter(this);$('.timerangepicker-container').on('click','.timerangepicker-display.hour .increment',function(){var value=$(this).siblings('.value');value.text(increment(value.text(),12,1,2))});$('.timerangepicker-container').on('click','.timerangepicker-display.hour .decrement',function(){var value=$(this).siblings('.value');value.text(decrement(value.text(),12,1,2))});$('.timerangepicker-container').on('click','.timerangepicker-display.minute .increment',function(){var value=$(this).siblings('.value');value.text(increment(value.text(),59,0,2))});$('.timerangepicker-container').on('click','.timerangepicker-display.minute .decrement',function(){var value=$(this).siblings('.value');value.text(decrement(value.text(),12,1,2))});$('.timerangepicker-container').on('click','.timerangepicker-display.period .increment, .timerangepicker-display.period .decrement',function(){var value=$(this).siblings('.value');var next=value.text()=="PM"?"AM":"PM";value.text(next)})});$(document).on('click',e=>{if(!$(e.target).closest('.timerangepicker-container').length){if($('.timerangepicker-container').is(":visible")){var timerangeContainer=$('.timerangepicker-container');if(timerangeContainer.length>0){var timeRange={from:{hour:timerangeContainer.find('.value')[0].innerText,minute:timerangeContainer.find('.value')[1].innerText,period:timerangeContainer.find('.value')[2].innerText},to:{hour:timerangeContainer.find('.value')[3].innerText,minute:timerangeContainer.find('.value')[4].innerText,period:timerangeContainer.find('.value')[5].innerText},};timerangeContainer.parent().find('input').val(timeRange.from.hour+":"+timeRange.from.minute+" "+timeRange.from.period+"-"+timeRange.to.hour+":"+timeRange.to.minute+" "+timeRange.to.period);timerangeContainer.remove()}}}});function increment(value,max,min,size){var intValue=parseInt(value);if(intValue==max){return('0'+min).substr(-size)}else{var next=intValue+1;return('0'+next).substr(-size)}}
function decrement(value,max,min,size){var intValue=parseInt(value);if(intValue==min){return('0'+max).substr(-size)}else{var next=intValue-1;return('0'+next).substr(-size)}}
</script>