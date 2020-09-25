@if($_table)
    <table class="table table-bordered table-condensed custom-column-table">
        <thead>
            <tr>
                @foreach($_table[0] as $column)
                <th class="text-center">{{ $column["label"] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody> 
                @foreach($_table as $row)
                    <tr>
                        @foreach($row as $column)
                        <td class="text-center">{!! $column["data"] !!}</td>
                        @endforeach
                    </tr>
                @endforeach
        </tbody>
    </table>
    <div class="pull-right paginat">{!! $_raw_table->render() !!}</div>
@else
    <div class="text-center" style="padding: 100px 0">NO RESULT FOUND</div>
@endif