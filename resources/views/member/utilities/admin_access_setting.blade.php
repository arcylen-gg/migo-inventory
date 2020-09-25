
@foreach($_page as $key => $page)
    <tr data-id="access-{{$page['segment'] or $page['code']}}" data-parent="{{ $page_parent or '' }}" >
        <td>
            <span >{!!$page['name'] or $page['label']!!} </span> 
            <span class="label label-info">{{array_has($page, 'user_settings') ? count($page['user_settings']) : count($page['submenu'])}} </span> 
        </td>
    </tr>
    @if(array_has($page, 'user_settings'))
        @foreach($page['user_settings'] as $key1=>$setting)
            <tr data-id="setting-{{$key}}-{{$key1}}" data-parent="access-{{$page['segment'] or $page['code']}}" >
                <td style="display: flex">
                    <span>
                        <label class="checkbox" style="margin: 0;">
                            <input class="form-group" type="checkbox" name="{{$page['code']."|".$setting}}" value="1" {{$page['setting_is_checked'][$key1] == 1 ? 'checked' : ''}}>
                            {{$setting}}
                        </label>
                    </span>
                </td>
            </tr>
        @endforeach
    @endif
    @if(array_has($page, 'submenu'))
        @include('member.utilities.admin_access_setting', ['_page' => $page['submenu'], 'page_parent' => "access-" . (isset($page['segment']) ? $page['segment'] : $page['code']) ])
    @endif
@endforeach