@if(isset($template))
    <div class="col-md-8 col-md-offset-2">
        <div class="form-group clearfix">
            <table style="width: 100%;background-color: {{$template->footer_background_color}}">
                <tbody>
                    <tr>
                        <td>
                            <div style="color: {{$template->footer_text_color}}">{!! $template->footer_txt !!}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-2"></div>
@endif