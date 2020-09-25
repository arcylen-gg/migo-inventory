@if(isset($template))
    <div class="col-md-8 col-md-offset-2">
        <div class="form-group clearfix">
            <table style="width: 100%;background-color: {{$template->header_background_color}}">
                <tbody>
                    <tr>
                        <td style="width: 180px">
                        @if(isset($message))
                            @if($template->header_image)
                                <img src="<?php echo $message->embed(URL::to('/').$template->header_image); ?>">
                            @endif
                        @else
                            @if($template->header_image)
                                <img src="<?php echo URL::to('/').$template->header_image; ?>">
                            @endif
                        @endif
                        </td>
                        <td>
                            <div style="color: {{$template->header_text_color}}">{!! $template->header_txt !!}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-2"></div>
@endif