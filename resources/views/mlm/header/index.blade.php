<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="@if(isset($icon)) {{$icon}} @else fa fa-question-circle @endif"></i></span>
        <div class="info-box-content">
            @if(isset($button))
              @if(count($button) >= 1)
                @foreach($button as $key => $value)
                  {!! $value !!}
                @endforeach
              @endif
            @endif
          <span class="info-box-text"><h2>@if(isset($title)) {{$title}} @endif</h2></span> 
          <span class="info-box-text" style="color: gray;"><small>@if(isset($sub)) {!! $sub !!} @endif</small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
</div>   
