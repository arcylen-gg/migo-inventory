<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
    </thead>
    <tbody>
        @if(!count($names) == 0)
            @foreach($names as $name)
            <tr class="prediction-choice" id="{{ $name->slot_no }}">
                <td class="text-left">
                    <label> {{ $name->slot_no }} </label><br>
                            {{ $name->first_name." ".$name->last_name  }}
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>