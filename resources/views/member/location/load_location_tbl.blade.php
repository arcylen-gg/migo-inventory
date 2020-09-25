<div class="table-responsive">
    <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th class="text-center" colspan="3">{{$title}} {{isset($parent_name) ? 'of '.$parent_name : ''}}
                    <a href="javascript:" link="/member/maintenance/location/location?parent_id={{ $parent_id or 0}}&title={{$title}}" size="md" class="pull-right popup">
                    <span class="fa fa-plus"></span>
                    </a>
                </th>
                <th class="hidden location-parent-id" var-name="{{$var_name or 'empty'}}">{{ $parent_id or 0}} </th>
            </tr>
        </thead>
        <tbody>
            @foreach($_location as $key=>$location)
            <tr class="">
                <td class="hidden location-id">{{ $location->locale_id}} </td>
                <td class="text-left location-data">
                    {{ $location->locale_name }}
                </td>
                <td style="width: 10%">
                    <a href="javascript:" link="/member/maintenance/location/location?parent_id={{ $parent_id or 0}}&title={{$title}}&id={{$location->locale_id}}" size="md" class="pull-right popup">
                        <span class="fa fa-pencil"></span>
                    </a>
                    
                </td>
                <td style="width: 10%">
                    <a href="javascript:" link="/member/maintenance/location/delete-location?title={{$title}}&id={{$location->locale_id}}" size="md" class="pull-right popup">
                        <span class="fa fa-trash"></span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>