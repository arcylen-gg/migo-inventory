<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th class="text-center">Warehouse Name</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>

    <tbody class="table-warehouse">
    @if(count($_warehouse) > 0)
    {!! $_warehouse_list !!}
    @else
    <tr>
        <td colspan="2" class="text-center">No Warehouse Found</td>
    </tr>
    @endif
    </tbody>
</table>