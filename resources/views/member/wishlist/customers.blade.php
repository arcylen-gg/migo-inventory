<div class="table-responsive">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center">Total Wish</th>
                {{-- <th class="text-center">Action</th> --}}
            </tr>
        </thead>
        <tbody class="table-warehouse">
            @if(count($_customer) > 0)
                @foreach($_customer as $customer)
                <tr>
                    <td>{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}</td>
                    <td class="text-center">{{ $customer->count }}</td>
                    {{-- <td class="text-center">
                        <div class="btn-group">
                          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-custom">
                            <li><a size="lg" link="" href="javascript:" class="popup"></a></li>
                          </ul>
                        </div>
                    </td> --}}
                </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="2">No data</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>