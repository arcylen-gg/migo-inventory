<div class="panel panel-default panel-block panel-title-block collapse" id="customize_column">
    <div class="panel-heading">
        <div class="col-md-6">
            <span class="color:gray;">
               <smell> Note: Drag and drop to change position. Check to show the field</smell>
            </span>
            <form class="global-submit" action="/member/report/accounting/sale/edit/filter" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="report_field_type" value="{{$filter}}">
                <ol class='soratble_fields'>
                @foreach($report_field as $key => $value)
                
                    <div class="col-md-12">
                        <li data-id="{{$key}}" k_c="c_{{$key}}"  class="sort_li" id="{{isset($report_field[$key]->report_field_position) == true ? $report_field[$key]->report_field_position : ''}}">
                                <input type="hidden" name="report_field_position[{{$key}}]" value="0" class="c_{{$key}}">
                                <div class="col-md-2"><i class="fa fa-arrows" aria-hidden="true"></i></div>
                                <div class="col-md-2"><input type="checkbox" class="checkbox_{{$key}}" value="{{$key}}" {{isset($report_field[$key]) == true ? 'checked' : ''}} name="report_field_module[{{$key}}]" /> </div>
                                <div class="col-md-8">{{$value->report_field_label}}</div>
                            
                        </li>
                    </div>
                @endforeach
                @foreach($report_field_default as $key => $value)
                    @if(!isset($report_field[$key]))
                        <div class="col-md-12">
                            <li data-id="{{$key}}" k_c="c_{{$key}}"  class="sort_li" id="">
                                    <input type="hidden" name="report_field_position[{{$key}}]" value="0" class="c_{{$key}}">
                                    <div class="col-md-2"><i class="fa fa-arrows" aria-hidden="true"></i></div>
                                    <div class="col-md-2"><input type="checkbox" class="checkbox_{{$key}}" value="{{$key}}"  name="report_field_module[{{$key}}]" /> </div>
                                    <div class="col-md-8">{{$value}}</div>
                            </li>
                        </div>
                    @endif
                @endforeach
                </ol>
                <button class="btn btn-primary col-md-12">Submit</button>
            </form>
            
        </div>
    </div>
</div>
 <style type="text/css">
 body.dragging, body.dragging * {
  cursor: move !important;
}

.dragged {
  position: absolute;
  opacity: 0.5;
  z-index: 2000;
}

ol.soratble_fields li.placeholder {
  position: relative;
  /** More li styles **/
}
ol.soratble_fields li.placeholder:before {
  position: absolute;
  /** Define arrowhead **/
}
</style>
 <script type="text/javascript" src="/assets/mlm/jquery-sortable.js"></script>
 <script type="text/javascript">
 $(function  () {
  $("ol.soratble_fields").sortable({
        start:function(event, ui) 
        {

        },
        stop: function(event, ui) 
        {
            var position_a = ui.item.index() + 1;
            $('.sort_li').each(function(i) { 

               $(this).data('id', i + 1);
               $(this).attr('data-id', i + 1);
               var k_c =  $(this).attr('k_c');
               $("." + k_c).val(i + 1);
               console.log(k_c);

            });
        },
        update: function( event, ui ) 
        {

        }
    });
});
 </script>